<?php

include('conecta.php');

$id_cliente = $_GET['id'];

$sql = "SELECT * FROM usuarios WHERE id= '$id_cliente'";
$resultado = mysqli_query($conectar, $sql);
$row = mysqli_fetch_assoc($resultado);

$correo = $row['email'];
$nombre = $row['nombre'];
$apellido = $row['apellido'];

$activo = $row['activo'];

if($activo == 1){
    $activo = 'Activo';
} else{
    $activo = 'Inactivo';
}






?>