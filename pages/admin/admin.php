<?php 

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


require '../../php/conecta.php';



//Cantidad de clientes
$sql_clientes = "SELECT COUNT(*) AS cantidad_clientes FROM usuarios WHERE id_rol = 3;";
$resultado = mysqli_query($conectar,$sql_clientes);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_clientes = $fila['cantidad_clientes'];

//Cantidad productos
$sql_productos = "SELECT COUNT(*) AS cantidad_productos FROM producto";
$resultado = mysqli_query($conectar,$sql_productos);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos = $fila['cantidad_productos'];

//Cantidad de productos vencidos
$sql_productos_vencidos = "SELECT COUNT(*) AS cantidad_vencidos FROM producto WHERE fecha_ven <= CURDATE()";
$resultado = $resultado = mysqli_query($conectar,$sql_productos_vencidos);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_vencidos = $fila['cantidad_vencidos'];

//Cantidad de productos proximos a vencer
$fecha_limite = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));
$sql_produc_prox = "SELECT COUNT(*) AS cantidad_proximos_a_vencer FROM producto WHERE fecha_ven BETWEEN CURDATE() AND '$fecha_limite'";
$resultado = mysqli_query($conectar,$sql_produc_prox);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_proximos_a_vencer = $fila['cantidad_proximos_a_vencer'];

//Cantida de productos fuera de stock
$sqlFueraStock = "SELECT COUNT(p.id) AS cantidad_fuera_stock
FROM producto p
LEFT JOIN stock s ON p.id = s.producto
WHERE s.stock_actual = 0 OR s.stock_actual IS NULL";
$resultado = mysqli_query($conectar, $sqlFueraStock);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_out_stock = $fila ['cantidad_fuera_stock'];

//Ventas del dia
$sqlVentas = "SELECT COUNT(*) AS total_ventas FROM factura WHERE estado_factura = 'Pagado'";
$resultado = mysqli_query($conectar,$sqlVentas);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_ventas_total = $fila ['total_ventas'];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo filemtime('../../css/dashboard.css'); ?>">
    <link rel="stylesheet"
        href="../../css/admin.css/adminInicio.css?v=<?php echo filemtime('../../css/admin.css/adminInicio.css'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    
    <title>Administrador</title>
</head>

<body>

   <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Dashboard</h1>
                    <h6>Home</h2>
            </div>
            <div class="estadisticas-contenedor">
                 <div class="row">

                    <div class="cajaE">
                        <h4>Clientes Totales</h4>
                        <h4><?php echo $cantidad_clientes; ?></h4>
                    </div>

                    <div class="cajaE">
                        <h4>Productos Totales</h4>
                        <h4><?php echo $cantidad_productos; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Total de Ventas</h4>
                        <h4><?= $cantidad_ventas_total?></h4>
                    </div>
                </div>

                <div class="row">

                    <div class="cajaE">
                        <h4>Productos Próximos a Expirar</h4>
                        <h4><?php echo $cantidad_productos_proximos_a_vencer; ?></h4>
                    </div>

                    <div class="cajaE">
                        <h4>Productos Expirados</h4>
                        <h4><?php echo $cantidad_productos_vencidos; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Productos Agotados</h4>
                        <h4><?php echo $cantidad_productos_out_stock; ?></h4>
                    </div>
                </div>
            </div>
            <div class="opciones">

                <div class="row">
                    <a href="agregar_usuario.php" class="box-opc">
                        <i class='bx bx-user-plus bx-lg'></i>
                        <h4>Añadir Nuevo Usuario</h4>
                    </a>


                    <a href="agregar_producto.php" class="box-opc">
                        <i class='bx bx-plus-medical bx-lg'></i>
                        <h4>Añadir Nuevo Medicamento</h4>
                    </a>

                    <a href="agregar_proveedor.php" class="box-opc">
                        <i class='bx bxs-truck icon bx-lg'></i>
                        <h4>Añadir Nuevo Proveedor</h4>
                    </a>

                    <a href="facturas.php" class="box-opc">
                        <i class='bx bxs-receipt bx-lg'></i>
                        <h4>Ver Facturas</h4>
                    </a>
                </div>

            </div>

    </section>



</body>
<script src="../../js/dashboard.js"></script>




</html>