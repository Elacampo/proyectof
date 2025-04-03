<?php

// Verificar si se recibió el ID del usuario a eliminar
if(isset($_GET['id_cat'])) {
    require 'conecta.php';
    // Obtener el ID del usuario a eliminar
    $userId = $conectar -> real_escape_string($_GET['id_cat']);
    
    $sql = "UPDATE categoria SET activo = 0 WHERE id_cat = $userId";

    // Ejecutar la consulta
    if($conectar->query($sql) === TRUE) {
        header("Location: ../pages/admin/categoria.php");
        exit();
    } else {
        header("Location: ../pages/admin/categoria.php");
        exit();
    }

    // Cerrar la conexión a la base de datos
    $conectar->close();
} else {
    // Si no se recibió el ID del usuario, redireccionar al usuario a una página de error o mostrar un mensaje
    header("Location: error_id_invalido.php");
    exit(); // Salir del script para evitar ejecución adicional
}

?>