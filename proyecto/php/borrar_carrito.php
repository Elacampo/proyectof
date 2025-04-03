<?php
session_start();

// Eliminar todos los productos del carrito
unset($_SESSION['carrito']);
// Reiniciar la cantidad de productos en el carrito a cero
$_SESSION['cantidad_productos_carrito'] = 0;

?>
