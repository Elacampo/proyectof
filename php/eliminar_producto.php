<?php

session_start();



if(isset($_GET['id'])){

    require 'conecta.php';

    $producto_id = $conectar -> real_escape_string($_GET['id']);
    $sql = "DELETE FROM producto WHERE id = $producto_id";

    if($conectar->query($sql) === TRUE) {
        if($_SESSION['rol'] == 1){
            header("Location: ../pages/admin/productos.php");
            exit(); 
        } else if ($_SESSION['rol'] == 2){
            header("Location: ../pages/empleado/productos.php");
            exit(); 
        }
    } 

    $conectar->close();
}else{

    header("Location: error_id_invalido.php");
    exit();
}

?>