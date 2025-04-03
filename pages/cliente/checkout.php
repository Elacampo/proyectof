    <?php
    require '../../php/conecta.php';

    session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


    function calcularTotal($carrito) {
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
        return number_format($total, 0, ',', '.');

    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
    </style>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>



        <?php include('includes/nav_bar.php');?>



        <section class="py-24 relative">
            <div class="w-full max-w-7xl px-4 md:px-5 lg-6 mx-auto">

                <h2 class="title font-manrope font-bold text-4xl leading-10 mb-8 text-center text-black">Carrito de
                    compra
                </h2>



                <div class="hidden lg:grid grid-cols-2 py-6">
                    <div class="font-normal text-xl leading-8 text-gray-500">Producto</div>
                    <p class="font-normal text-xl leading-8 text-gray-500 flex items-center justify-between">
                        <span class="w-full max-w-[200px] text-center" style="opacity:0;pointer-events: none;">Delivery
                            Charge</span>
                        <span class="w-full max-w-[260px] text-center">Cantidad</span>
                        <span class="w-full max-w-[200px] text-center">Total</span>
                    </p>
                </div>

                <!-- Productos -->
                <?php if (!empty($_SESSION['carrito'])): ?>
                <form id="formulario-factura" action="../../php/guardar_factura.php" method="post">

                    <?php  foreach ($_SESSION['carrito'] as $index => $producto): 

                    $sql_stock = $conectar->prepare("SELECT stock_actual FROM stock where producto = ?");
                    $sql_stock->bind_param("i",$producto['id']);
                    $sql_stock->execute();
                    $resultStock = $sql_stock->get_result();
                    $cantidad_stock = $resultStock->fetch_assoc();
                    $limite_stock = $cantidad_stock['stock_actual'];


                    $precio_formateado = number_format($producto['precio'], 0, ',', '.');?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 min-[550px]:gap-6 border-t border-gray-200 py-6">
                        
                        <div
                            class="flex items-center flex-col min-[550px]:flex-row gap-3 min-[550px]:gap-6 w-full max-xl:justify-center max-xl:max-w-xl max-xl:mx-auto">
                            <div class="flex items-center justify-center lg:justify-start">
                            <button type="button" onclick="eliminarProducto(<?php echo $index; ?>)"
                                class="text-red-500 hover:text-red-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                            <div class="img-box"><img src="../../img/productos/<?php echo $producto['imagen']; ?>"
                                    alt="<?php echo $producto['nombre']; ?>" class="xl:w-[140px]"></div>
                            <div class="pro-data w-full max-w-sm ">
                                <h5 class="font-semibold text-xl leading-8 text-black max-[550px]:text-center">
                                    <?php echo $producto['nombre']; ?></h5>
                                <p
                                    class="font-normal text-lg leading-8 text-gray-500 my-2 min-[550px]:my-3 max-[550px]:text-center">
                                    <?php echo $producto['categoria']; ?></p>
                                <h6 class="font-medium text-lg leading-8 text-black  max-[550px]:text-center">
                                    <?php echo $precio_formateado; ?></h6>
                            </div>
                        </div>
                        <div
                            class="flex items-center flex-col min-[550px]:flex-row w-full max-xl:max-w-xl max-xl:mx-auto gap-2">
                            <h6 style="opacity:0; pointer-events: none;"
                                class="font-manrope font-bold text-2xl leading-9 text-black w-full max-w-[176px] text-center">
                                $15.00 <span class="text-sm text-gray-300 ml-3 lg:hidden whitespace-nowrap">(Delivery
                                    Charge)</span></h6>
                            <div class="flex items-center w-full mx-auto justify-center">
                                <button
                                    class="group rounded-l-full px-6 py-[18px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-200 hover:border-gray-300 hover:bg-gray-50"
                                    onclick="decrementQuantity(this)">
                                    <svg class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"
                                        fill="none">
                                        <path d="M16.5 11H5.5" stroke="" stroke-width="1.6" stroke-linecap="round" />
                                        <path d="M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                            stroke-linecap="round" />
                                        <path d="M16.5 11H5.5" stroke="" stroke-opacity="0.2" stroke-width="1.6"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                                <input type="number" name="cantidad_producto_<?php echo $index; ?>"
                                    id="cantidad-producto-<?php echo $index; ?>"
                                    class="appearance-none border-y border-gray-200 outline-none text-gray-900 font-semibold text-lg w-full max-w-[118px] min-w-[80px] placeholder:text-gray-900 py-[15px] text-center bg-transparent"
                                    placeholder="1" value="<?php echo $producto['cantidad'];?>" min="1"
                                    max="<?= $limite_stock ?>" onchange="checkQuantity(this)">


                                <button
                                    class="group rounded-r-full px-6 py-[18px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:shadow-gray-200 hover:border-gray-300 hover:bg-gray-50"
                                    onclick="incrementQuantity(this)">
                                    <svg class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                                        xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"
                                        fill="none">
                                        <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-width="1.6"
                                            stroke-linecap="round" />
                                        <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-opacity="0.2"
                                            stroke-width="1.6" stroke-linecap="round" />
                                        <path d="M11 5.5V16.5M16.5 11H5.5" stroke="" stroke-opacity="0.2"
                                            stroke-width="1.6" stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>
                            <h6
                                class="text-black font-manrope font-bold text-2xl leading-9 w-full max-w-[176px] text-center">
                                <span class="product-price"
                                    data-unit-price="<?php echo $producto['precio']; ?>"><?php echo $producto['precio'] * $producto['cantidad']; ?></span>
                            </h6>
                        </div>
                        
                    </div>
                    <?php endforeach; 
                    ?>
                    <div class="bg-gray-50 rounded-xl p-6 w-full mb-8 max-lg:max-w-xl max-lg:mx-auto">
                        <div class="flex items-center justify-between w-full py-6">
                            <p class="font-manrope font-medium text-2xl leading-9 text-gray-900">Total</p>
                            <h6 id="total-price" class="font-manrope font-medium text-2xl leading-9 text-black">
                                $<?php echo calcularTotal($_SESSION['carrito']); ?></h6>
                        </div>
                    </div>
                    <div class="flex items-center flex-col sm:flex-row justify-center gap-3 mt-8">
                        <button type="button" id="generar-factura" onclick="confirmarGenerarFactura()"
                            class="rounded-full w-full max-w-[280px] py-4 text-center justify-center items-center bg-blue-600 font-semibold text-lg text-white flex transition-all duration-500 hover:bg-indigo-700">Generar
                            Factura
                            <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="23" height="22"
                                viewBox="0 0 23 22" fill="none">
                                <path d="M8.75324 5.49609L14.2535 10.9963L8.75 16.4998" stroke="white"
                                    stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <?php else:
                        echo '<p>No hay productos en el carrito.</p>'
                        ?>
                        <?php endif; ?>
                        <!-- Fin de Productos -->


                </form>

            </div>
            </div>
        </section>


        <?php include('includes/footer.php')?>

        <script>
        function confirmarGenerarFactura() {
            // Mostrar mensaje de confirmación
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea generar la factura?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, generar factura',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario
                    document.getElementById('formulario-factura').submit();
                }
            });
        }
        // Función para verificar y ajustar el valor del input si excede el límite máximo
        function checkQuantity(input) {
            var maxAllowed = parseInt(input.getAttribute('max')); // Obtener el valor máximo permitido
            var currentValue = parseInt(input.value); // Obtener el valor actual del input

            // Verificar si el valor es negativo o cero
            if (currentValue <= 0) {
                // Establecer el valor mínimo como 1
                input.value = 1;
                // Mostrar un mensaje de error indicando que se ha ingresado un valor inválido
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '¡Por favor ingresa una cantidad válida!',
                });
            }

            // Verificar si el valor excede el límite máximo
            else if (currentValue > maxAllowed) {
                // Si el valor actual excede el límite máximo permitido, ajustarlo al máximo permitido
                input.value = maxAllowed;
                // Mostrar un mensaje de error indicando que se ha alcanzado el límite máximo
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '¡Has alcanzado el límite máximo de stock para este producto!',
                });
            }

            // Actualizar el precio total
            updateTotalPrice(input);
        }


        function incrementQuantity(button) {
            event.preventDefault()
            var input = button.parentElement.querySelector('input');
            // Obtener el valor máximo permitido del input
            var maxAllowed = parseInt(input.getAttribute('max'));
            // Obtener el valor actual del input
            var currentValue = parseInt(input.value);
            // Incrementar la cantidad si no excede el máximo permitido
            if (currentValue < maxAllowed) {
                input.value = currentValue + 1;
                updateTotalPrice(input);
                checkQuantity(input); // Agregar esta línea para verificar la cantidad actualizada
            } else {
                // Mostrar un mensaje o tomar alguna otra acción si se alcanza el máximo permitido
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '¡Has alcanzado el límite máximo de stock para este producto!',
                });
            }

            // Actualizar el valor del campo de entrada
            input.setAttribute('value', input.value);

        }

        function decrementQuantity(button) {
            event.preventDefault()
            var input = button.parentElement.querySelector('input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateTotalPrice(input);
                checkQuantity(input); // Agregar esta línea para verificar la cantidad actualizada
            }

            // Actualizar el valor del campo de entrada
            input.setAttribute('value', input.value);
            event.preventDefault()
        }

        function updateTotalPrice(input) {
            var priceElement = input.parentElement.nextElementSibling.querySelector('.product-price');
            var unitPrice = parseFloat(priceElement.dataset
                .unitPrice); // Obtener el precio unitario del atributo de datos
            var newPrice = parseFloat(input.value) * unitPrice; // Multiplicar la cantidad por el precio unitario
            priceElement.innerText = newPrice.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP'
            });
            calculateTotalPrice(); // Llama a la función para actualizar el precio total

            // Actualizar el valor del input hidden
            var hiddenInput = input.parentElement.querySelector('input[type="hidden"]');
            hiddenInput.value = input.value;
        }

        function calculateTotalPrice() {
            var total = 0;
            document.querySelectorAll('.product-price').forEach(function(element) {
                // Convertir el precio colombiano a un número para sumar correctamente
                total += parseFloat(element.innerText.replace(/\./g, '').replace(/,/g, '.').replace(/[^\d.]/g,
                    ''));
            });
            // Formatear el total como precio colombiano
            document.getElementById('total-price').innerText = total.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP'
            });
        }

        // Formatear precios de productos al cargar la página
        document.querySelectorAll('.product-price').forEach(function(element) {
            var price = parseFloat(element.innerText.replace(/\./g, '').replace(/,/g, '.').replace(/[^\d.]/g,
                ''));
            element.innerText = price.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP'
            });
        });

        function eliminarProducto(index) {
        // Mostrar mensaje de confirmación
        Swal.fire({
            title: '¿Eliminar producto?',
            text: "¿Seguro que deseas eliminar este producto del carrito?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamar a la función PHP para eliminar el producto del carrito
                window.location.href = '../../php/eliminar_producto_carrito.php?index=' + index;
            }
        });
    }

        </script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </body>

    </html>