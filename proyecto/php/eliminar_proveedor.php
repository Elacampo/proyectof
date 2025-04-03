<?php

// Verificar si se recibió el ID del usuario a eliminar
if(isset($_GET['proveedor_id'])) {
    require 'conecta.php';
    
    // Obtener el ID del usuario a eliminar
    $userId = $conectar -> real_escape_string($_GET['proveedor_id']);

    // Consulta para eliminar el usuario de la base de datos
    $sql = "DELETE FROM proveedores WHERE proveedor_id = $userId";

    // Ejecutar la consulta
    if($conectar->query($sql) === TRUE) {
        // Si la eliminación fue exitosa, redireccionar al usuario a una página de éxito o mostrar un mensaje
        header("Location: ../pages/admin/proveedores.php");
        exit();
    } else {
        // Si hubo algún error al eliminar, redireccionar al usuario a una página de error o mostrar un mensaje
        header("Location: ../pages/admin/proveedores.php");
        exit();
    }

    // Cerrar la conexión a la base de datos
    $conectar->close();
} else {
    // Si no se recibió el ID del usuario, redireccionar al usuario a una página de error o mostrar un mensaje
    header("Location: error_id_invalido.php");
    exit();
}

?>
