<?php

// Verificar si se recibió el ID del usuario a eliminar
if(isset($_GET['id_cat'])) {
    require 'conecta.php';

    $userId = $conectar -> real_escape_string($_GET['id_cat']);

    // Consulta para eliminar el usuario de la base de datos
    $sql = "UPDATE categoria SET activo = 1 WHERE id_cat = $userId";

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