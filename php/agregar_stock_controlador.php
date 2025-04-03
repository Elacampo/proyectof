<?php
include('conecta.php');

if(isset($_POST['btnEnviar'])){

    $cantidad_stock = $_POST['stock_actual'];
    $producto = $_POST['nombre_producto'];
    $ubicacion = $_POST['area'];


    // Actualizar la ubicación en la tabla producto
    $sql_actualizar_ubicacion = "UPDATE producto SET ubicacion = ? WHERE id = ?";
    $stmt_actualizar_ubicacion = mysqli_prepare($conectar, $sql_actualizar_ubicacion);

    if($stmt_actualizar_ubicacion){
        mysqli_stmt_bind_param($stmt_actualizar_ubicacion, "si", $ubicacion, $producto);
        $resultado_actualizar_ubicacion = mysqli_stmt_execute($stmt_actualizar_ubicacion); // EJECUTÓ
        mysqli_stmt_close($stmt_actualizar_ubicacion); 
    }


    //CONSULTAR UBICACION DESPUÉS DE ACTUALIZAR. CON ESA UBICACION SE IRA A LA TABLA ESPACIO Y SE ACTUALIZARÁ EL ESTADO
    $sql_verificar_espacio = "SELECT ubicacion FROM producto WHERE id = $producto ";
    $resultado_verificar_espacio = mysqli_query($conectar, $sql_verificar_espacio);
    $row_verificar_espacio = mysqli_fetch_assoc($resultado_verificar_espacio);
    $espacio_actual_producto = $row_verificar_espacio['ubicacion'];

    $sql_verificar_capacidad = "SELECT capacidad_max FROM espacio WHERE id_bodega = '$espacio_actual_producto'";
    $resultado_verificar_capacidad = mysqli_query($conectar, $sql_verificar_capacidad);
    $row_verificar_capacidad = mysqli_fetch_assoc($resultado_verificar_capacidad);

    if($cantidad_stock >= $row_verificar_capacidad['capacidad_max']){
        $sql_actualizar_estado = "UPDATE espacio SET estado = 'Completo ' WHERE id_bodega = '$espacio_actual_producto'";
        mysqli_query($conectar, $sql_actualizar_estado);

    }else{
        $sql_actualizar_estado = "UPDATE espacio SET estado = 'Ocupado' WHERE id_bodega = '$espacio_actual_producto'";
        mysqli_query($conectar, $sql_actualizar_estado);
    }

    $sql_insertar_stock = "INSERT INTO stock(stock_actual,producto,ubicacion) VALUES (?,?,?)";
    $stmt_insertar_stock = mysqli_prepare($conectar, $sql_insertar_stock);

    if($stmt_insertar_stock){
        mysqli_stmt_bind_param($stmt_insertar_stock, "iis", $cantidad_stock, $producto,$ubicacion);
        $resultado_insertar_stock = mysqli_stmt_execute($stmt_insertar_stock); //EJECUTO 

        if($resultado_insertar_stock){
            echo "<script>
                        Swal.fire({
                            title: 'Stock creado!',
                            text: 'El stock se ha creado correctamente!',
                            icon: 'success',
                            didClose: () => { 
                                window.location.href = '../admin/stock.php';
                            }
                        });   
                    </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Stock no creado!',
                        text: 'El stock no se ha creado correctamente!',
                        icon: 'error'
                    });   
                </script>";
        }
        mysqli_stmt_close($stmt_insertar_stock);
    } else{
        echo "Error al preparar la consulta: " . mysqli_error($conectar);
    }
    mysqli_close($conectar);

} else {

    $sql_producto = "SELECT p.id, p.nombre_producto, p.ubicacion, e.area
    FROM producto p
    LEFT JOIN espacio e ON p.ubicacion = e.id_bodega
    WHERE p.ubicacion IS NULL AND p.id NOT IN (SELECT s.producto FROM stock s)";
    $resultProductos = $conectar ->query($sql_producto);
    $fila_producto = $resultProductos->fetch_assoc();

    $sql = "SELECT * FROM producto WHERE ubicacion=NULL";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);
        

    //Consulta para obtener el cod de bodega
    $sql_espacio = "SELECT id_bodega, area FROM espacio WHERE estado = 'Disponible'";
    $result_espacio = $conectar->query($sql_espacio);


}

?>