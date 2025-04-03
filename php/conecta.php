<?php 
//Declarar variables en donde se guardarán los valores de la conexión
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "sistema";

$conectar = mysqli_connect($servidor,$usuario,$password,$bd);
$conectar -> set_charset("utf8");
if($conectar -> connect_error){
    die("Error al conectar a la base de datos de la página.".$connectar->connect_error);
}

?>