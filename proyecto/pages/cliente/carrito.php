<?php if (!isset($_SESSION['cantidad_productos_carrito'])) {
    $_SESSION['cantidad_productos_carrito'] = 0;
} 
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="relative">
    <button data-modal-target="default-modal" data-modal-toggle="default-modal" type="button">
        <svg class="w-9 h-9 text-gray-800 dark:text-white sm:mr-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
        </svg>
        <div id="cantidad-productos-carrito"
            class="absolute bottom-0 left-0 bg-red-500 w-6 h-4 flex items-center justify-center rounded-full text-white text-sm">
            <?=  $_SESSION['cantidad_productos_carrito'];?>
        </div>
    </button>
</div>


<!-- Main modal -->
<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Carrito de compras
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <!-- Shopping Cart Content -->

                <div class="flex flex-col space-y-2">
                    <!-- Product 1 -->
                    <?php
                    $total = 0; 
                    if (!empty($_SESSION['carrito'])): ?>
                    <?php foreach ($_SESSION['carrito'] as $producto): 
                        $subtotal = $producto['precio'] * $producto['cantidad'];
                        $total += $subtotal;


                    
                        $precio_formateado = number_format($producto['precio'], 0, ',', '.');
                        ?>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="../../img/productos/<?= $producto['imagen'];?>"
                                alt="<?php echo $producto['nombre']; ?>" class="w-12 h-12 object-cover rounded-md">
                            <div>
                                <span class="text-gray-900"><?php echo $producto['nombre']; ?></span>
                                <p class="text-xs text-gray-500"><?php echo $producto['categoria']; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span id="cantidad-<?php echo $producto['id']; ?>"
                                class="text-gray-700"><?php echo $producto['cantidad']; ?></span>
                        </div>
                        <span class="text-gray-700"><?php echo $precio_formateado;?> COP</span>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p>No hay productos en el carrito.</p>
                    <?php endif; ?>

                    <!-- Total -->
                    <div class="flex items-center justify-between font-semibold">
                        <span>Total:</span>
                        <span><?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <div>
                    <a href="checkout.php">
                    <button data-modal-hide="default-modal" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        <?php echo (empty($_SESSION['carrito'])) ? 'disabled' : ''; ?>>
                        Finalizar compra
                    </button>
                    </a>
                    <button data-modal-hide="default-modal" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Continuar
                        comprando</button>
                </div>
                <button id="eliminar-todos" onclick=eliminarCarrito()
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-1/3">
                    Eliminar carrito
                </button>
            </div>

        </div>
    </div>
</div>


<script>
function eliminarCarrito() {
    // Realiza una solicitud AJAX a un archivo PHP para eliminar el carrito
    Swal.fire({
        title: "Eliminar carrito",
        text: "¿Estás seguro de que deseas eliminar todos los productos del carrito?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../php/borrar_carrito.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status == 200) {
                    // El carrito ha sido eliminado exitosamente
                    Swal.fire({
                        title: "¡Éxito!",
                        text: "El carrito ha sido eliminado correctamente.",
                        icon: "success"
                    }).then(() => {
                        // Actualizar la interfaz de usuario
                        document.getElementById('cantidad-productos-carrito').innerText = '0';
                        // Eliminar los elementos del carrito en el DOM
                        var carritoContent = document.querySelector('.flex.flex-col.space-y-2');
                        carritoContent.innerHTML = '<p>No hay productos en el carrito.</p>';
                    });
                } else {
                    // Hubo un error al intentar eliminar el carrito
                    console.error('Error al eliminar el carrito');
                }
            };

            xhr.onerror = function() {
                // Manejar errores de conexión
                console.error('Error de conexión al intentar eliminar el carrito');
            };

            // Envía la solicitud
            xhr.send();
        }
    });
}
</script>