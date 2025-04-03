<?php

// Verificar si se recibió el ID del usuario a eliminar
if(isset($_GET['id'])) {
    require 'conecta.php';

    $userId = $conectar -> real_escape_string($_GET['id']);

    // Consulta para eliminar el usuario de la base de datos
    $sql = "UPDATE usuarios SET activo = 1 WHERE id = $userId";

    // Ejecutar la consulta
    if($conectar->query($sql) === TRUE) {
        header("Location: ../pages/admin/usuarios.php");
        exit();
    } else {
        header("Location: ../pages/admin/usuarios.php");
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