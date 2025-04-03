<?php
// Incluir el archivo de conexión a la base de datos
require 'conecta.php';

// Iniciar sesión
session_start();
$id_usuario = $_SESSION['id'];

// Función para calcular el total de la factura
function calcularTotal($carrito) {
    $total = 0;
    foreach ($carrito as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    return $total;
}

// Verificar si hay productos en el carrito
if (!empty($_SESSION['carrito'])) {
    // Calcular el total de la factura
    $total_factura = calcularTotal($_SESSION['carrito']);
    
    // Insertar la factura en la base de datos
    $cliente_id = $id_usuario; // Obtener el ID del cliente de la sesión (suponiendo que está almacenado en la sesión)
    $fecha_emision = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
    $fecha_limite_validez = date('Y-m-d', strtotime('+7 days')); // Obtener la fecha límite de validez (7 días después de la emisión)
    $estado_factura = 'Pendiente'; // Establecer el estado inicial de la factura
    
    $insert_factura = $conectar->prepare("INSERT INTO factura (id_cliente, fecha_emision, fecha_limite_validez, estado_factura) VALUES (?, ?, ?, ?)");
    $insert_factura->bind_param("isss", $cliente_id, $fecha_emision, $fecha_limite_validez, $estado_factura);
    $insert_factura->execute();
    
    // Obtener el ID de la factura recién insertada
    $id_factura = $insert_factura->insert_id;
    
    // Insertar los detalles de la factura en la tabla detalles_factura
    foreach ($_SESSION['carrito'] as $index => $producto) {
        $id_producto = $producto['id'];
        $precio = $producto['precio'];
        $cantidad = $_POST["cantidad_producto_$index"];
        
        $insert_detalle = $conectar->prepare("INSERT INTO detalles_factura (id_factura, id_producto, precio, cantidad) VALUES (?, ?, ?, ?)");
        $insert_detalle->bind_param("iidi", $id_factura, $id_producto, $precio, $cantidad);
        $insert_detalle->execute();

    }
    
    // Eliminar los productos del carrito después de crear la factura
     $_SESSION['cantidad_productos_carrito'] = 0;
    unset($_SESSION['carrito']);
    
    // Mostrar un mensaje de éxito o redirigir a una página de confirmación de compra
    header('Location:   ../pages/cliente/ver_factura.php?id_factura='.$id_factura);
} else {
    // Si no hay productos en el carrito, mostrar un mensaje de error o redirigir a la página de productos
    echo "No hay productos en el carrito.";
}
?>
