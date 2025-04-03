<?php

include ('conecta.php');

if(isset($_POST['btnEnviar'])){

    
    $nombre_producto = ucfirst($_POST['nombre']);
    $descripcion = ucfirst($_POST['descripcion']);
    $categoria = $_POST['categoria'];
    $unidad_medida = $_POST['unidad_medida'];
    $precio = $_POST['precio'];
    $ubicacion = NULL;
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];    
    $proveedor = $_POST['proveedor'];

     // Verificar si se subió un archivo de imagen
     if ($_FILES['imagen']['size'] > 0) {
        $nombreImagen = $_FILES['imagen']['name']; // Nombre original del archivo
        $rutaTempImagen = $_FILES['imagen']['tmp_name']; // Ruta temporal del archivo en el servidor
        $rutaDestinoImagen = '../../img/productos/' . $nombreImagen; // Ruta donde se desea guardar la imagen
        move_uploaded_file($rutaTempImagen, $rutaDestinoImagen);
    }
    
    
    $sql_verificar_nombre = "SELECT * FROM producto WHERE nombre_producto = ?";
    $stmt_verificar_nombre = mysqli_prepare($conectar,$sql_verificar_nombre);
    mysqli_stmt_bind_param($stmt_verificar_nombre,"s", $nombre_producto);
    mysqli_stmt_execute($stmt_verificar_nombre);
    $resultado_verificar_nombre = mysqli_stmt_get_result($stmt_verificar_nombre);
    $fila_verificar_nombre = mysqli_fetch_assoc($resultado_verificar_nombre);

    if(mysqli_num_rows($resultado_verificar_nombre) > 0){
        echo "<script>
        Swal.fire({
            title: 'Nombre de producto ya en uso!',
            text: 'El nombre del producto ingresado ya se encuentra registrado en el sistema.',
            icon: 'error'
        });   
      </script>";
    } else{
        $sql_insertar_producto = "INSERT INTO producto(nombre_producto,descripcion,categoria,unidad_medida,ubicacion,fecha_ingreso,fecha_ven,proveedor,precio_producto,imagen_producto) values (?,?,?,?,?,?,?,?,?,?)";
        $stmt_insertar_producto = mysqli_prepare($conectar,$sql_insertar_producto);

        if($stmt_insertar_producto){
            mysqli_stmt_bind_param($stmt_insertar_producto,"ssissssids", $nombre_producto,$descripcion,$categoria,$unidad_medida,$ubicacion,$fecha_ingreso,$fecha_vencimiento,$proveedor,$precio,$nombreImagen);
            $resultado_insertar_producto = mysqli_stmt_execute($stmt_insertar_producto);

            if($resultado_insertar_producto){
                echo "<script>
                Swal.fire({
                    title: 'Producto creado!',
                    text: 'El producto se ha creado correctamente!',
                    icon: 'success',
                    didClose: () => { 
                        window.location.href = 'productos.php';
                    }
                });   
            </script>";
            }else{
                echo "<script>
                Swal.fire({
                    title: 'Producto no creado!',
                    text: 'El producto no se ha creado correctamente!',
                    icon: 'error'
                });   
            </script>";
            }
            mysqli_stmt_close($stmt_insertar_producto);
        }else{
            echo "Error al preparar la consulta: " . mysqli_error($conectar);

        }
    }
mysqli_close($conectar);

}else{

    $sql = "SELECT * FROM producto";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);

    //Consulta para obtener el cod de bodega
    $sql_espacio = "SELECT id_bodega, area FROM espacio WHERE estado = 'Disponible' ";
    $result_espacio = $conectar->query($sql_espacio);

    //Consulta para obtener todas las categorias activas. 
    $sql_categorias = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
    $resultCategorias = $conectar ->query($sql_categorias);

    //Consulta para obtener el nombre del proveedor
    $sql_proveedor = "SELECT proveedor_id, nombre FROM proveedores";
    $resultProveedor = $conectar->query($sql_proveedor);

}







?>