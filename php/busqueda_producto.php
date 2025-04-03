<?php
require 'conecta.php';

// Comprobar si se envió un nombre de producto para buscar
if (isset($_GET['producto_nombre'])) {
    // Sanitizar y preparar el nombre del producto para la consulta
    $producto_nombre = '%' . $_GET['producto_nombre'] . '%'; // Para buscar coincidencias parciales
    // Consulta SQL para buscar productos por nombre
    $sql = "SELECT p.id, p.nombre_producto, p.imagen_producto, p.precio_producto, p.categoria FROM producto p 
    INNER JOIN stock s on p.id = s.producto
    WHERE p.nombre_producto LIKE ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param("s", $producto_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Mostrar los resultados de la búsqueda en el formato deseado
    if($resultado->num_rows > 0) {
        // Mostrar el encabezado de la sección de productos
        echo '<section id="Projects" class="w-fit mx-auto grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">';

        // Iterar sobre los resultados y mostrarlos en formato HTML
        while($fila = $resultado->fetch_assoc()) {
            // Obtener el nombre de la categoría del producto
            $categoria_id = $fila['categoria'];
            $sql_categoria = $conectar->prepare("SELECT nombre FROM categoria WHERE id_cat = ?");
            $sql_categoria->bind_param("i", $categoria_id);
            $sql_categoria->execute();
            $resultCategoria = $sql_categoria->get_result();
            $categoria_resultado = $resultCategoria->fetch_assoc();

            // Formatear el precio del producto
            $precio_formateado = number_format($fila['precio_producto'], 2, ',', '.');

            if(empty($fila['imagen_producto'])){
                $imagen = 'no-imagen.jpg';
            }else{
                $imagen = $fila['imagen_producto'];
            }

             // Mostrar el producto en formato HTML
             echo '<div class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl">';
             echo '<a href="#" onclick="return false;" style="cursor:default">';
             echo '<img src="../../img/productos/' . $imagen . '" alt="Product" class="h-80 w-72 object-cover rounded-t-xl" />';
             echo '<div class="px-4 py-3 w-72 bg-blue-50">';
             echo '<span class="text-gray-500 mr-3 uppercase text-xs">'. $categoria_resultado['nombre'] .'</span>';
             echo '<p class="text-lg font-bold text-black truncate block capitalize">' . $fila['nombre_producto'] . '</p>';
             
             echo '<div class="flex items-center">';
             echo '<p class="text-lg font-semibold text-black cursor-auto my-3">COP $' . $precio_formateado . '</p>';
             echo '<div class="ml-auto flex">';
             echo '<a href="ver_producto.php?id='.$fila['id'].'" class="mr-4 icon-link" title="Ver más">';
             echo '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
             echo '<path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />';
             echo '<path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
             echo '</svg>';
             echo '</a>';
             
             echo '<a href="#" title="Añadir al carrito" class="icon-link">';
             echo '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
             echo '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />';
             echo '</svg>';
             echo '</a>';
             echo '</div>';
             echo '</div>';
             echo '</div>';
             echo '</a>';
             echo '</div>';
             
        }
        // Cerrar la sección de productos
        echo '</section>';
    } else {
        // Mostrar un mensaje si no se encontraron resultados
        echo '<section id="Projects" class="w-fit mx-auto grid grid-cols-1 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">';
        
        echo '<div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">   
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div>
          <span class="font-medium">Aviso!</span> No se encontraron resultados para la búsqueda
        </div>
      </div>';
        echo '</section>';
    }
} else {
    // Si no se proporcionó un nombre de producto para buscar, muestra un mensaje de error o realiza otra acción
    echo 'No se proporcionó un nombre de producto para buscar.';
}
?>
