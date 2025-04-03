<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ob_start(); // Iniciar el búfer de salida
require '../../php/conecta.php';
if (!isset($_SESSION['cantidad_productos_carrito'])) {
    $_SESSION['cantidad_productos_carrito'] = 0;
}   

include('includes/nav_bar.php');



if(empty($_SESSION['id'])){
    header("location: ../login.php");
}

if($_SESSION['rol'] == 1){
    header("location: ../admin/admin.php");
}

if($_SESSION['rol'] == 2){
    header("location: ../empleado/farmaceutico.php");
}

$hoy = date('Y-m-d');
$sql = $conectar -> prepare("SELECT nombre_producto, imagen_producto, precio_producto, categoria, fecha_ven FROM producto WHERE fecha_ven > $hoy");
$sql ->execute();
$resultado = $sql->get_result(); // Get the result set
$rows = $resultado->fetch_all(MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Catalogo</title>
</head>
<style>
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    font-family: 'Poppins', sans-serif;
}
</style>

<body class="bg-gray-100">



    <div class="max-w-2xl mx-auto p-6">
        <div class="text-center p-1">
            <h1 class="font-bold text-4xl mb-4">Catalogo</h1>
        </div>
        <form class="flex items-center">
            <label for="voice-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="text" id="producto-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Busca el nombre del producto aqui...">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer clear-search"
                    style="display: none;">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </form>

        <div class="mt-4">
            <label for="categoria-dropdown" class="mr-2 text-gray-700">Categoría:</label>
            <select id="categoria-dropdown"
                class="py-3 px-4 pe-9 block border-gray-200 rounded-full text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                <option selected="" value=''>Todas</option>
                <?php
                $sql_lista_categorias = $conectar -> prepare("SELECT * FROM categoria WHERE activo = 1");
                $sql_lista_categorias ->execute();
                $resultado_lista = $sql_lista_categorias->get_result(); // Get the result set
                $num_filas_lista = $resultado_lista->num_rows;
                $rows_lista_categorias = $resultado_lista->fetch_all(MYSQLI_ASSOC);
                foreach($rows_lista_categorias as $fila) {
                    echo '<option value="' . $fila['id_cat'] . '">' . $fila['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>

    </div>



    <!-- ✅ Grid Section - Starts Here 👇 -->
    <div id="resultado_busqueda"></div>
    <div class="bg-gray-100">

        <section id="Projects"
            class="w-fit mx-auto grid grid-cols-1 lg:grid-cols-3 sm:grid-cols-2 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">
            <?php 
    
                if(!empty($_REQUEST['pag'])){ 
                    $_REQUEST['pag'] = $_REQUEST['pag'];
                }else{
                    $_REQUEST['pag'] = '1';
                }
    
                

    
                if($_REQUEST['pag'] == ''){$_REQUEST['pag'] = '1';}
                
                $registro = '6';
                $pagina = $_REQUEST['pag'];

                $total_consulta = "SELECT COUNT(*) AS total FROM producto WHERE fecha_ven > ?";
                $stmt = $conectar->prepare($total_consulta);
                $stmt->bind_param("s", $hoy);
                $stmt->execute();
                $total_resultado = $stmt->get_result();
                $total_filas = $total_resultado->fetch_assoc()['total'];
                $num_total_paginas = ceil($total_filas / $registro); // Calculamos el total de páginas
                
                // Asegurarse de que no haya páginas adicionales si solo hay un producto vencido
                if ($num_total_paginas > 1 && $num_total_paginas % $registro == 1) {
                    $num_total_paginas--;
                }
    
            
                if(is_numeric($pagina)){
                    $inicio = (($pagina-1)*$registro);
                }else{
                    $inicio = 0;
                }
                if(isset($_GET['pag'])) {   
                    // Si el valor proporcionado no es un número o es menor que 1, redirigir a la primera página
                    if(!is_numeric($_GET['pag']) || intval($_GET['pag']) < 1) {
                        header("Location: ?pag=1");
                        exit;
                    }
                    $pagina_actual = intval($_GET['pag']);
                    // Si la página actual es mayor que el número total de páginas, redirigir a la primera página
                    if($pagina_actual > $num_total_paginas) {
                        header("Location: ?pag=1");
                        exit; // Terminar la ejecución del script después de la redirección
                    }
                } else {
                    // Redirigir automáticamente a la primera página si no se proporciona ninguna página
                    header("Location: ?pag=1");
                    exit;
                }
                
    

                
                $sql_productos = "SELECT p.* FROM producto p 
                INNER JOIN stock s on p.id = s.producto 
                WHERE p.fecha_ven > '$hoy'
                LIMIT $inicio,$registro";
                
                $sentencia_productos = $conectar ->prepare($sql_productos);
                $sentencia_productos->execute();
                $resultado_pr = $sentencia_productos->get_result(); // Get the result set
                $resultado_articulos = $resultado_pr->fetch_all(MYSQLI_ASSOC);
        
            
            foreach($resultado_articulos as $filas){ 
                ?>

            <!--   ✅ Product card 1 - Starts Here 👇 -->
            <div class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl ">



                <a href="#" onclick="return false;" class="producto " style="cursor:default">
                    <?php 
                            if(empty($filas['imagen_producto'])){
                                $imagen = 'no-imagen.jpg';
                            }else{
                                $imagen = $filas['imagen_producto'];
                            }
    
                            $precio_formateado = number_format($filas['precio_producto'], 2, ',', '.');
    
                            $categoria_id = $filas['categoria'];
                            $sql_categoria = $conectar->prepare("SELECT nombre,id_cat FROM categoria WHERE id_cat = ?");
                            $sql_categoria->bind_param("i", $categoria_id);
                            $sql_categoria->execute();
                            $resultCategoria = $sql_categoria->get_result();
                            $categoria_resultado = $resultCategoria->fetch_assoc();                        
                            
                        ?>
                    <!-- Imagen -->
                    <img src="../../img/productos/<?= $imagen?>" alt="Product"
                        class="h-80 w-72 object-cover rounded-t-xl" />
                    <div class="px-4 py-3 w-72 bg-blue-50 producto ">
                        <span class="text-gray-500 mr-3 uppercase text-xs"><?= $categoria_resultado['nombre']?></span>
                        <p class="text-lg font-bold text-black truncate block capitalize">
                            <?= $filas['nombre_producto'];?>
                        </p>

                        <div class="flex items-center">
                            <p class="text-lg font-semibold text-black cursor-auto my-3">
                                <?= 'COP $'.$precio_formateado;?>
                            </p>
                            <!-- icono  -->
                            <div class="ml-auto flex">
                                <a href="ver_producto.php?id=<?=$filas['id']?>" class="mr-4" title="Ver más">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>

                                </a>


                                <!-- Añadir al carrito-->
                                <button type="submit" onclick="enviar_carrito(
                                        $('#id_producto<?php echo $filas['id'];?>').val(),
                                        $('#nombre_producto<?php echo $filas['id']; ?>').val(),
                                        $('#precio_producto<?php echo $filas['id'];?>').val(),
                                        $('#cantidad_producto<?php echo $filas['id'];?>').val(),
                                        $('#imagen_producto<?php echo $filas['id'];?>').val(),
                                        $('#categoria_producto<?php echo $filas['id']?>').val())">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="id_producto" id="id_producto<?php echo $filas['id'];?>"
                                    value="<?php echo $filas['id']?>">
                                <input type="hidden" name="nombre_producto"
                                    id="nombre_producto<?php echo $filas['id'];?>"
                                    value="<?php echo $filas['nombre_producto']?>">
                                <input type="hidden" name="imagen_producto"
                                    id="imagen_producto<?php echo $filas['id'];?>" value="<?php echo $imagen?>">

                                <input type="hidden" name="categoria_producto"
                                    id="categoria_producto<?php echo $filas['id'];?>"
                                    value="<?php echo $categoria_resultado['nombre']?>">

                                <input type="hidden" name="precio_producto"
                                    id="precio_producto<?php echo $filas['id'];?>"
                                    value="<?php echo $filas['precio_producto']?>">
                                <input type="hidden" name="cantidad_producto"
                                    id="cantidad_producto<?php echo $filas['id'];?>" value="1">
                                <!-- Añadir al carrito-->
                            </div>

                        </div>
                    </div>

                </a>


            </div>
            <?php } ?>
            <!--   🛑 Product card 1 - Ends Here  -->
        </section>

        <div id="pagination-container" class="flex flex-col items-center mb-8 px-4 mx-auto mt-8">
            <div class="font-sans flex justify-end space-x-1 select-none">

                <!-- Boton Anterior -->
                <?php if(isset($_GET['pag']) && $_GET['pag'] > 1) { ?>
                <a href="?pag=<?= $_GET['pag'] - 1;?>"
                    class="px-4 py-2 font-bold text-gray-500 bg-gray-300 rounded-md hover:bg-blue-400 hover:text-white">
                    Ant.
                </a>
                <?php } ?>

                <!-- Botones de paginación -->
                <?php for($contador = 1; $contador <= $num_total_paginas; $contador++) { ?>
                <a href="?pag=<?= $contador ?>"
                    class="px-4 py-2 <?= isset($_GET['pag']) && $contador == $_GET['pag'] ? 'bg-blue-400 text-white' : 'text-gray-700 bg-gray-200' ?> rounded-md hover:bg-blue-400 hover:text-white"
                    style="transition: all 0.2s ease;">
                    <?= $contador ?>
                </a>
                <?php } ?>

                <!-- Boton Siguiente -->
                <?php if(!isset($_GET['pag']) || (isset($_GET['pag']) && $_GET['pag'] < $num_total_paginas)) { ?>
                <a href="?pag=<?= isset($_GET['pag']) ? $_GET['pag'] + 1 : 2 ?>"
                    class="px-4 py-2 font-bold text-gray-500 bg-gray-300 rounded-md hover:bg-blue-400 hover:text-white"
                    style="transition: all 0.2s ease;">
                    Sig.
                </a>
                <?php } 
                
                ?>

            </div>
        </div>
    </div>

    <div id="carrito-container"></div>




    <?php include('includes/footer.php')?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <?php include("../../php/carrito_controlador.php");?>





    <script>
    // Función para agregar un producto al carrito
    function enviar_carrito(id_producto, nombre_producto, precio_producto, cantidad_producto, imagen_producto,
        categoria_producto) {
        var parametros = {
            "id_producto": id_producto,
            "nombre_producto": nombre_producto,
            "precio_producto": precio_producto,
            "cantidad_producto": cantidad_producto,
            "imagen_producto": imagen_producto,
            "categoria_producto": categoria_producto
        };

        // Realizar una solicitud AJAX al controlador de carrito
        $.ajax({
            data: parametros,
            url: '../../php/carrito_controlador.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                // Verificar si la solicitud fue exitosa
                if (response.status === 'success') {
                    // Actualizar el valor de la cantidad de productos en el carrito en la interfaz de usuario
                    $('#cantidad-productos-carrito').text(response.cantidad_carrito);
                    // Mostrar un mensaje de éxito
                    Swal.fire({
                        title: "Producto agregado al carrito",
                        text: "El producto se ha agregado correctamente al carrito.",
                        icon: "success"
                    });
                } else {
                    // Mostrar un mensaje de error si la solicitud no fue exitosa
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un error al agregar el producto al carrito. Por favor, inténtalo de nuevo más tarde.",
                        icon: "error"
                    });
                }
            },
            error: function(response, error) {
                // Mostrar un mensaje de error si ocurre un error durante la solicitud AJAX
                console.error('Error al agregar producto al carrito:', error);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al agregar el producto al carrito. Por favor, inténtalo de nuevo más tarde.",
                    icon: "error"
                });
            }
        });
    }
    </script>



    <script>
    $(document).ready(function() {
        // Evento de clic en el icono 'x' para limpiar el input
        $('.clear-search').on('click', function() {
            $('#producto-search').val(''); // Limpiar el valor del input
            $('.producto').show(); // Mostrar todos los productos
            $('#pagination-container').show(); // Mostrar la paginación
            $('.categoria').removeClass(
                'bg-blue-500 text-white'); // Deseleccionar la categoría si está seleccionada
            $('#resultado_busqueda').empty(); // Vaciar el resultado de la búsqueda
            $(this).hide(); // Ocultar el icono de limpieza
        });

        // Evento de entrada en el campo de búsqueda
        $('#producto-search').on('input', function() {
            var productName = $(this).val().trim();

            // Mostrar u ocultar el icono 'x' según si el campo de búsqueda tiene contenido
            if (productName === '') {
                $('.clear-search').hide();

            } else {
                $('.clear-search').show();
            }

            // Si hay texto en el campo de búsqueda, realizar la búsqueda AJAX
            if (productName !== '') {
                $('.producto').hide();
                $('#pagination-container').hide();
                $('.categoria').removeClass('bg-blue-500 text-white');
                $('#resultado_busqueda').empty();

                $.ajax({
                    url: '../../php/busqueda_producto.php',
                    type: 'GET',
                    data: {
                        producto_nombre: productName
                    },
                    success: function(response) {
                        $('#resultado_busqueda').html(response);
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            } else {
                // Si el campo de búsqueda está vacío, mostrar todos los productos
                $('.producto').show();
                $('#pagination-container').show();
                $('.categoria').removeClass('bg-blue-500 text-white');
                $('#resultado_busqueda').empty();
            }
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // Evento de clic en el icono 'x' para limpiar el input
        $('.clear-search').on('click', function() {
            $('#producto-search').val(''); // Limpiar el valor del input
            $('.producto').show(); // Mostrar todos los productos
            $('#pagination-container').show(); // Mostrar la paginación
            $('#categoria-dropdown').val(''); // Restablecer la selección de categoría
            $('#resultado_busqueda').empty(); // Vaciar el resultado de la búsqueda
            $(this).hide(); // Ocultar el icono de limpieza
        });

        // Evento de cambio en el menú desplegable de categorías
        $('#categoria-dropdown').change(function() {
            var categoriaSeleccionada = $(this).val();

            $('.producto').hide();
            $('#pagination-container').hide();

            if (categoriaSeleccionada === '') {
                $('.producto').show();
                $('#pagination-container').show();
                $('#categoria-dropdown').val('');
                $('#resultado_busqueda').empty();
            } else {
                $.ajax({
                    url: '../../php/busqueda.php',
                    type: 'GET',
                    data: {
                        categoria_seleccionada: categoriaSeleccionada
                    },
                    success: function(response) {
                        $('#resultado_busqueda').html(response);
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            }
        })

        // Evento de entrada en el campo de búsqueda
        $('#producto-search').on('input', function() {
            var productName = $(this).val().trim();

            // Mostrar u ocultar el icono 'x' según si el campo de búsqueda tiene contenido
            if (productName === '') {
                $('.clear-search').hide();
            } else {
                $('.clear-search').show();
            }

            // Si hay texto en el campo de búsqueda, realizar la búsqueda AJAX
            if (productName !== '') {
                $('.producto').hide();
                $('#pagination-container').hide();
                $('#categoria-dropdown').val(''); // Restablecer la selección de categoría
                $('#resultado_busqueda').empty();

                $.ajax({
                    url: '../../php/busqueda_producto.php',
                    type: 'GET',
                    data: {
                        producto_nombre: productName
                    },
                    success: function(response) {
                        $('#resultado_busqueda').html(response);
                    },
                    error: function() {
                        alert('Ocurrió un error al procesar la solicitud.');
                    }
                });
            } else {
                // Si el campo de búsqueda está vacío, mostrar todos los productos
                $('.producto').show();
                $('#pagination-container').show();
                $('#resultado_busqueda').empty();
            }
        });
    });
    </script>


</body>

</html>