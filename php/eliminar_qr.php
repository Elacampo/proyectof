<?php

session_start();
include("conecta.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener el nombre de la imagen actual del producto
    $sql = "SELECT qr_imagen FROM producto WHERE id = $id";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);
    $nombreQR = $row['qr_imagen'];

    // Eliminar la imagen del servidor
    if(!empty($nombreQr) && $nombreQR != 'no-qr.png') {
        $rutaImagen = '../img/codigos_qr/' . $nombreQR;
        if(file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Actualizar la columna imagen_producto en la base de datos
    $sql_update = "UPDATE producto SET qr_imagen = '' WHERE id = $id";
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
