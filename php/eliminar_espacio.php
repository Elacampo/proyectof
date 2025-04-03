<?php

// Verificar si se recibió el ID del usuario a eliminar
if(isset($_GET['id_bodega'])) {
    require 'conecta.php';

    // Obtener el ID del usuario a eliminar
    $userId = $conectar -> real_escape_string($_GET['id_bodega']);

    // Consulta para eliminar el usuario de la base de datos
    $sql = "DELETE FROM espacio WHERE id_bodega = '$userId' ";

    // Ejecutar la consulta
    if($conectar->query($sql) === TRUE) {
        // Si la eliminación fue exitosa, redireccionar al usuario a una página de éxito o mostrar un mensaje
        header("Location: ../pages/admin/espacio.php");
        exit(); // Salir del script para evitar ejecución adicional
    } else {
        // Si hubo algún error al eliminar, redireccionar al usuario a una página de error o mostrar un mensaje
        header("Location: ../pages/admin/espacio.php");
        exit(); // Salir del script para evitar ejecución adicional
    }

    // Cerrar la conexión a la base de datos
    $conectar->close();
} else {
    // Si no se recibió el ID del usuario, redireccionar al usuario a una página de error o mostrar un mensaje
    header("Location: error_id_invalido.php");
    exit(); // Salir del script para evitar ejecución adicional
}

?>
