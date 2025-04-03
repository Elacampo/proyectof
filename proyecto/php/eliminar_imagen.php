<?php
session_start();    

include("conecta.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener el nombre de la imagen actual del producto
    $sql = "SELECT imagen_producto FROM producto WHERE id = $id";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);
    $nombreImagen = $row['imagen_producto'];

    // Eliminar la imagen del servidor
    if(!empty($nombreImagen) && $nombreImagen != 'no-imagen.jpg') {
        $rutaImagen = '../img/productos/' . $nombreImagen;
        if(file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Actualizar la columna imagen_producto en la base de datos
    $sql_update = "UPDATE producto SET imagen_producto = 'no-imagen.jpg' WHERE id = $id";
    mysqli_query($conectar, $sql_update);
}

// Redireccionar de vuelta a la página de edición del producto
if($_SESSION['rol'] == 1){
    header("Location: ../pages/admin/editar_producto.php?id=$id");
    exit(); 
} else if ($_SESSION['rol'] == 2){
    header("Location: ../pages/empleado/editar_producto.php?id=$id");
    exit(); 
}

exit();
