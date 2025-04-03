<?php
session_start();
// Verificar si se recibieron los datos del producto
if(isset($_POST['id_producto']) && isset($_POST['nombre_producto']) && isset($_POST['precio_producto']) && isset($_POST['cantidad_producto']) && isset($_POST['imagen_producto']) && isset($_POST['categoria_producto'])) {
    // Crear un arreglo asociativo con los detalles del producto
    $producto = array(
        'id' => $_POST['id_producto'],
        'nombre' => $_POST['nombre_producto'],
        'precio' => $_POST['precio_producto'],
        'cantidad' => $_POST['cantidad_producto'],
        'imagen' => $_POST['imagen_producto'],
        'categoria' => $_POST['categoria_producto']
    );

    // Verificar si el carrito ya existe en la sesión
    if(isset($_SESSION['carrito'])) {
        // Si el producto ya está en el carrito, actualizar la cantidad
        if(array_key_exists($producto['id'], $_SESSION['carrito'])) {
            $_SESSION['carrito'][$producto['id']]['cantidad'] += $producto['cantidad'];
        } else {
            // Si el producto no está en el carrito, agregarlo
            $_SESSION['carrito'][$producto['id']] = $producto;
        }
    } else {
        // Si el carrito no existe en la sesión, crearlo y agregar el producto
        $_SESSION['carrito'] = array($producto['id'] => $producto);
    }
        
    $_SESSION['cantidad_productos_carrito'] += $producto['cantidad'];

    // Enviar respuesta de éxito
    echo json_encode(array('status' => 'success', 'message' => 'Producto agregado al carrito exitosamente.', 'cantidad_carrito' => $_SESSION['cantidad_productos_carrito']));
} else {
    
}
?>
