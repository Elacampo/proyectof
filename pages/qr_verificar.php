<?php
$id = $_GET['id'];
session_start();

if (empty($_SESSION['id'])) {
    header("location:  producto.php?id=$id");
    exit(); 
}

if ($_SESSION['rol'] == 1) {
    header("location: admin/ver_producto.php?id=$id"); 
    exit();
}

if ($_SESSION['rol'] == 2) {
    header("location: empleado/ver_producto.php?id=$id");
    exit();
}

if ($_SESSION['rol'] == 3) {
    header("location: cliente/ver_producto.php?id=$id");
    exit();
}

exit();
?>