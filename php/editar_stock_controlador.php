<?php

include('conecta.php');


if(isset($_POST['btnEnviar'])){

        $stock_producto = $_POST['producto_stock'];
        $stock_actual = $_POST['stock_actual'];
        $ubicacion = $_POST['area']; 

        $sql = "UPDATE stock SET stock_actual = $stock_actual WHERE producto = $stock_producto";

        $resultado = mysqli_query($conectar, $sql);

        
        if($resultado){

            $sql = "SELECT * FROM stock WHERE producto = $stock_producto";
            $resultado = mysqli_query($conectar, $sql);
            $row = mysqli_fetch_assoc($resultado);

            $sql_producto = "SELECT nombre_producto FROM producto WHERE id=".$row['producto'];
            $result_producto = mysqli_query($conectar,$sql_producto);
            $row_producto = mysqli_fetch_assoc($result_producto);
            $producto = $row_producto['nombre_producto'];

            $valor_cod_bodega = $row['ubicacion'];
            $stock_actual = $row['stock_actual'];

            $sql_area = "SELECT area FROM espacio WHERE id_bodega= '".$valor_cod_bodega."'"; 
            $result_area = mysqli_query($conectar,$sql_area);
            $row_area = mysqli_fetch_assoc($result_area);
            $area =  $row_area['area'];
            
            echo "<script>
                Swal.fire({
                    title: 'Se ha actualizado el stock!',
                    text: 'El stock se ha actualizado correctamente!',
                    icon: 'success',
                    didClose: () => { 
                        window.location.href = 'stock.php';
                    }
                });   
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'No se ha actualizado el stock!',
                    text: 'El stock no se ha podido actualizar correctamente!',
                    icon: 'error'
                });
                window.location.assign('stock.php');
            </script>";
        }
        mysqli_close($conectar);

} else{
    
    $stock_producto = $_GET['stockProducto'];   

    $sql = "SELECT * FROM stock WHERE producto = $stock_producto";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);

    $sql_producto = "SELECT nombre_producto FROM producto WHERE id=".$row['producto'];
    $result_producto = mysqli_query($conectar,$sql_producto);
    $row_producto = mysqli_fetch_assoc($result_producto);
    $producto = $row_producto['nombre_producto'];

    $valor_cod_bodega = $row['ubicacion'];

    $sql_area = "SELECT area FROM espacio WHERE id_bodega= '".$valor_cod_bodega."'"; 
    $result_area = mysqli_query($conectar,$sql_area);
    $row_area = mysqli_fetch_assoc($result_area);
    $area =  $row_area['area'];


    $stock_actual = $row['stock_actual'];

 

    mysqli_close($conectar);
}




?>