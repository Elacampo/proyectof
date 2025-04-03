<?php

include('conecta.php');

$id_ubicacion = $_GET['id'];

$sql = "SELECT * FROM espacio WHERE id_bodega= '$id_ubicacion'";
$resultado = mysqli_query($conectar, $sql);
$row = mysqli_fetch_assoc($resultado);

$stock_query = "SELECT s.stock_actual, p.nombre_producto
                    FROM stock s
                    INNER JOIN producto p ON s.producto = p.id
                    WHERE s.ubicacion = '$id_ubicacion'";
        $stock_resultado = mysqli_query($conectar, $stock_query);
        $stock_row = mysqli_fetch_assoc($stock_resultado);

$cod_bogeda = $row['id_bodega'];
$cap_max = $row['capacidad_max'];
$area = $row['area'];
$estado = $row['estado'];

$cantidad_stock = isset($stock_row['stock_actual']) ? $stock_row['stock_actual'] : 0;
$nombre_producto = isset($stock_row['nombre_producto']) ? $stock_row['nombre_producto'] : "No asignado";





?>