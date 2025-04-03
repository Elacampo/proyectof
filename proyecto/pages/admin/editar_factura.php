<?php
include('../../php/conecta.php');



session_start();

if(empty($_SESSION['id'])){
    header("location: ../login.php");
}

if($_SESSION['rol'] == 2){
    header("location: ../empleado/farmaceutico.php");
}

if($_SESSION['rol'] == 3){
    header("location: ../cliente/cliente.php");
}



$alerta = '';
if (isset($_POST['btnActualizar'])) {
    $id_factura = $_POST['id_factura'];
    $estado_factura = $_POST['estado_factura'];



    //Guardar fechas originales del factura
    $sql_fechas = "SELECT fecha_emision, fecha_limite_validez FROM factura WHERE id_factura = $id_factura";
    $resultado_fechas = mysqli_query($conectar, $sql_fechas);
    $row_fechas = mysqli_fetch_assoc($resultado_fechas);
    $fecha_emision_original = $row_fechas['fecha_emision'];
    $fecha_limite_original = $row_fechas['fecha_limite_validez'];

    $update_estado = $conectar->prepare("UPDATE factura SET 
    fecha_emision = ?, fecha_limite_validez= ?, estado_factura = ? 
    WHERE id_factura = ?");
    $update_estado->bind_param('sssi', $fecha_emision_original, $fecha_limite_original, $estado_factura, $id_factura);
    
    $response = array();

    if($update_estado->execute()){
        if($estado_factura == 'Pagado'){
            $sql_productos_factura = $conectar->prepare("SELECT id_producto, cantidad FROM detalles_factura WHERE id_factura = ?");
            $sql_productos_factura->bind_param('i', $id_factura);
            $sql_productos_factura->execute();
            $result_productos = $sql_productos_factura->get_result();

            while($row_producto = $result_productos->fetch_assoc()){
                $id_producto = $row_producto['id_producto'];
                $cantidad = $row_producto['cantidad'];

                $sql_update_stock = mysqli_prepare($conectar, "UPDATE stock SET stock_actual = stock_actual - ? WHERE producto = ?");
                mysqli_stmt_bind_param($sql_update_stock, 'ii', $cantidad, $id_producto);
                
                if(mysqli_stmt_execute($sql_update_stock)){
                    header("Location: ../admin/facturas.php?success=true&message=El+estado+de+la+factura+se+ha+actualizado+correctamente");
                    exit;
                }else{
                    echo "Error al actualizar el stock: " . mysqli_error($conectar);
                }
            }
        }else{
            header("Location: ../admin/facturas.php?success=true&message=El+estado+de+la+factura+se+ha+actualizado+correctamente");
            exit;
        }
    }else{
        $alerta = "<script>
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al actualizar el estado de la factura',
            icon: 'error',
            didClose: () => { 
                window.location.href = '../pages/admin/facturas.php';
            }
        });   
        </script>";
    }
}else{
    $id_factura = $_GET['id_factura'];
    $sql = "SELECT estado_factura FROM factura WHERE id_factura = $id_factura";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);
    $estado = $row['estado_factura'];

    $estados_posibles = ['Pendiente','Pagado','Vencida','Rechazada'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Estado de la factura</title>
    <style>
    /* Estilos para el contenedor principal */
    .container {
        margin: 20px;
        border-radius: 20px;
        justify-content: center;
        display: flex;
    }

    /* Estilos para el título */
    h2.title {
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
        /* Color del texto */
    }

    /* Estilos para el párrafo */
    p.info-message {
        font-size: 16px;
        color: #666;
        /* Color del texto */
        margin-bottom: 10px;
    }

    /* Estilos para el select */
    select {
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Estilos para el botón */
    input[type="submit"] {
        padding: 10px 20px;
        /* Espaciado interno */
        font-size: 16px;
        /* Tamaño del texto */
        border: none;
        /* Elimina el borde */
        border-radius: 5px;
        background-color: #4CAF50;
        /* Color de fondo */
        color: white;
        /* Color del texto */
        cursor: pointer;
        /* Cambia el cursor al pasar por encima */
        transition: background-color 0.3s;
        /* Transición suave */
    }

    /* Estilos cuando el cursor está sobre el botón */
    input[type="submit"]:hover {
        background-color: #45a049;
        /* Cambia el color de fondo al pasar por encima */
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-message {
        font-size: 14px;
        color: #888;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>

    <?= $alerta ?>
    <div class="container">

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <h2 class="title">Estado de la factura</h2>
            <p class="info-message">Al cambiar el estado de la factura a 'Pagado', se realizará automáticamente una
                deducción del stock correspondiente de los productos asociados a esta factura. </p>
            <p class="info-message">Esta acción es irreversible y afectará directamente a la gestión de inventario. Por
                favor, asegúrese de que este cambio es necesario antes de proceder.</p>

            <div style="display:flex; gap:20px; align-items:center">
                <label for="estado">Estado actual:</label>
                <select name="estado_factura" <?php echo ($estado == 'Pagado') ? 'disabled' : ''; ?>>
                    <?php foreach ($estados_posibles as $estado_posible) : ?>
                    <option value="<?= $estado_posible ?>" <?= ($estado == $estado_posible) ? 'selected' : '' ?>>
                        <?= $estado_posible ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="id_factura" value="<?= $id_factura ?>">
            <input type="submit" value="Actualizar" name="btnActualizar" <?php echo ($estado == 'Pagado') ? 'disabled' : ''; ?> onclick="confirmUpdate();" style="opacity: <?php echo ($estado == 'Pagado') ? '0.5' : '1'; ?>; cursor: <?php echo ($estado == 'Pagado') ? 'not-allowed' : 'pointer'; ?>;">
        </form>

    </div>
</body>

</html>