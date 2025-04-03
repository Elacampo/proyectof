<?php 
include 'conecta.php';	

$email = $conectar ->real_escape_string($_POST['email']);
$psw = $conectar ->real_escape_string($_POST['password']);

session_start();
$_SESSION['email'] = $email;

$consulta = "SELECT *  FROM `usuarios` WHERE `email` = '$email' and `password` = '$psw'";
$resultado = mysqli_query($conectar,$consulta);

$filas = mysqli_fetch_array($resultado);

if($filas['id_rol'] == 1){ //Administrador
    header("location:../pages/admin.php");
} else if($filas['id_rol']== 2){ // Farmaceutico
    header("location:../pages/farmaceutico.php");
} else if($filas['id_rol'] == 3){ // Cliente
    header("location:../pages/farmaceutico.php");
} else{

    ?>
    <?php
    include("../pages/login.php");
    ?>
    <h1 class="bad">ERROR</h1>
    <?php
}

mysqli_free_result($resultado);
mysqli_close($conectar);

?>