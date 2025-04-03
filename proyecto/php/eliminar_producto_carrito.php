<?php
session_start();

// Verificar si se ha recibido un índice válido para el producto a eliminar
if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];

    // Verificar si el índice existe en el carrito
    if (isset($_SESSION['carrito'][$index])) {
        // Eliminar el producto del carrito usando unset()
        unset($_SESSION['carrito'][$index]);
        $_SESSION['cantidad_productos_carrito'] = $_SESSION['cantidad_productos_carrito'] - 1;
        // Redirigir de vuelta a la página del carrito
        header("Location: ../pages/cliente/checkout.php");
        exit();
    }
}

header("Location: ../pages/cliente/checkout.php");
exit(); 
?>
