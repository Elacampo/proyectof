<?php
    include('conecta.php');

        $id = $_GET['id'];
        $sql = "SELECT * FROM producto WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);

        $nombre = $row['nombre_producto'];  
        $unidad_medida = $row['unidad_medida'];
        $precio =  $row['precio_producto'];
        $imagen = $row['imagen_producto'];
        $descripcion = $row['descripcion'];
        $cod_bodega = $row['ubicacion'];

        
        if (empty($imagen) && $imagen == NULL) {
            $imagen = 'no-imagen.jpg';
        }

        $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = $id";
        $resultado_stock = mysqli_query($conectar, $sql_comprobar_stock);

        $activarH4 = FALSE;
        if(mysqli_num_rows($resultado_stock) > 0){
            // Si está en STOCK 
            $activarH4 = FALSE; 
        }else{
            // Si no está en STOCK 
            $activarH4 = TRUE; 
        }
    
        //Fecha de ingreso y vencimiento FORMATEADA
        $today = date('Y-m-d');
        $fecha_ingreso_formateada = date('d-m-Y', strtotime($row['fecha_ingreso']));
        $fecha_ven_formateada = date('d-m-Y', strtotime($row['fecha_ven']));
        
        $activarArea = FALSE;

        if($row['ubicacion'] === NULL){
            $activarArea = TRUE;
        }else{
            $activarArea = FALSE;
            // Consulta para obtener el nombre de la ubicacion
            $sqlArea = "SELECT area FROM espacio WHERE id_bodega = '". $row['ubicacion'] . "'";
            $resultArea = $conectar->query($sqlArea);
            $area = $resultArea->fetch_assoc()['area'];
        }



        // Consulta para obtener el nombre del proveedor
        $sqlProveedor = "SELECT nombre FROM proveedores WHERE proveedor_id = ". $row['proveedor'];
        $resultProveedor = $conectar->query($sqlProveedor);
        $proveedor = $resultProveedor->fetch_assoc()['nombre'];

        // Consulta para obtener el nombre de la categoria
        $sqlCategoria = "SELECT nombre FROM categoria WHERE id_cat = ". $row['categoria'];
        $resultCategoria = $conectar->query($sqlCategoria);
        $categoria = $resultCategoria->fetch_assoc()['nombre'];

        // Consultar para obtener el stock actual
        $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
        $resultStock = $conectar->query($sqlStock);
        $stock_row = $resultStock->fetch_assoc();
        $stock_actual = $stock_row['stock_total'] ?? 0;



        mysqli_close($conectar);
?>
