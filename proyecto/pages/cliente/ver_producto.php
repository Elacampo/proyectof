<?php

require '../../php/conecta.php';

session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(empty($_SESSION['id'])){
    header("location: ../login.php");
}

if($_SESSION['rol'] == 1){
    header("location: ../admin/admin.php");
}

if($_SESSION['rol'] == 2){
    header("location: ../empleado/farmaceutico.php");
}

$id_usuario = $_SESSION['id'];        
$id = $_GET['id'];

//Consulta producto
$sql_producto = "SELECT * FROM producto WHERE id= $id";
$resultado = mysqli_query($conectar, $sql_producto);
$row = mysqli_fetch_assoc($resultado);

//Consulta nombre categoria
$categoria_id = $row['categoria'];
$sql_categoria = $conectar->prepare("SELECT nombre FROM categoria WHERE id_cat = ?");
$sql_categoria->bind_param("i", $categoria_id);
$sql_categoria->execute();
$resultCategoria = $sql_categoria->get_result();
$categoria_resultado = $resultCategoria->fetch_assoc();                
$nombre_categoria = $categoria_resultado['nombre'];

//Consulta nombre proveedor
$proveedor_id = $row['proveedor'];
$sql_proveedor = $conectar->prepare("SELECT nombre FROM proveedores WHERE proveedor_id = ?");
$sql_proveedor ->bind_param("i",$proveedor_id);
$sql_proveedor ->execute();
$result_proveedor = $sql_proveedor ->get_result();
$proveedor_resultado = $result_proveedor->fetch_assoc();
$nombre_proveedor = $proveedor_resultado['nombre'];

//Consulta disponibilidad de stock
$sql_stock = $conectar->prepare("SELECT stock_actual FROM stock where producto = ?");
$sql_stock->bind_param("i",$id);
$sql_stock->execute();
$resultStock = $sql_stock->get_result();
$cantidad_stock = $resultStock->fetch_assoc();
$stock_producto = $cantidad_stock['stock_actual'];

$nombre = $row['nombre_producto'];
$descripcion = $row['descripcion'];

if(empty($row['imagen_producto'])){
    $imagen = 'no-imagen.jpg';
}else{
    $imagen = $row['imagen_producto'];
}

if(empty($row['qr_imagen'])){
    $imagen_qr = 'no-qr.png';
}else{
    $imagen_qr = $row['qr_imagen'];
}

$today = date('Y-m-d');
$fecha_ven_formateada = date('d-m-Y', strtotime($row['fecha_ven']));
$precio_formateado = number_format($row['precio_producto'], 3, ',', '.');


        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>

<style>
body {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
</style>

<body>

    <?php include('includes/nav_bar.php');?>





    <section class="bg-white py-16 px-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="cliente.php        "
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="catalogo.php"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Catalogo</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span
                            class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400"><?= $nombre?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mx-auto container grid place-items-center grid-cols-1 md:grid-cols-2">
            <div>
                <img src="../../img/productos/<?=$imagen?>" alt="pink blazer"
                    class="h-[25rem] hover:scale-105 transition duration-500 cursor-pointer object-cover" />
            </div>
            <div>
                <div>
                    <p class="text-gray-400 text-xl "><span class="text-sm ml-1"><?= $nombre_categoria;?></span></p>
                    <h3
                        class="block antialiased tracking-normal  text-3xl font-semibold leading-snug text-inherit mb-4">
                        <?= $nombre;?>
                    </h3>
                </div>
                <hr class="my-8">
                <h5 class="block antialiased tracking-normal  text-xl font-semibold leading-snug text-inherit">
                    <?= '$ '.$precio_formateado;?>
                </h5>
                <hr class="my-8">
                <h1 class="block antialiased tracking-normal font-semibold leading-snug text-inherit mt-8">
                    Stock actual: <?= $stock_producto?>
                </h1>
                <hr class="my-8">

                <?php
                if($stock_producto <= 0){
                    $disabled = 'disabled';
                    $min = 0;
                }else{
                    $disabled = '';
                    $min = 1;
                }
                ?>
                <div class="flex flex-col md:flex-row md:items-center ">


                    <div class="md:ml-4 flex flex-row md:flex-row">
                        <button id="btn-add-carrito"
                            class="align-middle select-none font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none bg-gray-900 w-52 md:mr-2"
                            type="button" data-ripple-light="true" <?=$disabled?>>
                            Añadir al carrito
                        </button>
                        <button
                            class="relative align-middle select-none font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20 shrink-0"
                            type="button" data-ripple-dark="true" <?=$disabled?>>
                            <span class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 justify-center flex mt-5">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab"
                data-tabs-toggle="#default-styled-tab-content"
                data-tabs-active-classes="text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500"
                data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
                role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab"
                        data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile"
                        aria-selected="false">Información detallada</button>
                </li>
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab"
                        aria-controls="dashboard" aria-selected="false">Datos de interes</button>
                </li>
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab"
                        aria-controls="settings" aria-selected="false">Codigo QR</button>
                </li>
            </ul>
        </div>

        <div id="default-styled-tab-content ">
            <div class="hidden p-4 rounded-lg bg-blue-50 dark:bg-gray-800 " id="styled-profile" role="tabpanel"
                aria-labelledby="profile-tab">
                <?php
                    $lineas = explode("\n", $descripcion);

                    foreach ($lineas as $linea) {
                        if (strpos($linea, "-") === 0) {
                            echo "<h3 class='text-2xl font-extrabold dark:text-white'>" . substr($linea, 2) . "</h3>";
                        } else {
                            echo "<p class='text-sm text-gray-500 dark:text-gray-400 leading-loose'>" . nl2br($linea) . "</p>";
                        }
                    }
                
                ?>

            </div>

            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel"
                aria-labelledby="dashboard-tab">

                <div>
                    <div class="mt-6 border-t border-gray-100">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Proveedor</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <?= $nombre_proveedor?>
                                </dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de vencimiento</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <?= $fecha_ven_formateada?>
                                </dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Unidad de medida</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <?= $row['unidad_medida']?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

            </div>



            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-settings" role="tabpanel"
                aria-labelledby="settings-tab">
                <img src="../../img/codigos_qr/<?= $imagen_qr;?>" class="block mx-auto w-25 md:w-1/6" alt="">
            </div>
        </div>

    </section>




    <footer class="bg-white text-gray-600 body-font">
        <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
            <a class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900">
                <img src="../../img/icon.png" alt="" class="h-10">
                <span class="ml-3 text-xl">Salud en línea</span>
            </a>
            <p class="text-sm text-gray-500 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">©
                2024 Andres Mendez — Elaine Campo
            </p>
            <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
                <a class="text-gray-500">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        class="w-5 h-5" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                    </svg>
                </a>
                <a class="ml-3 text-gray-500">
                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        class="w-5 h-5" viewBox="0 0 24 24">
                        <path
                            d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                        </path>
                    </svg>
                </a>
                <a class="ml-3 text-gray-500">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                    </svg>
                </a>
            </span>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
    // Función para agregar un producto al carrito
    $(document).ready(function() {
        $("#btn-add-carrito").on("click", function() {
            // Obtener los datos del producto
            var id_producto = <?= $id ?>;
            var nombre_producto = "<?= $nombre ?>";
            var precio_producto = <?= $row['precio_producto'] ?>;
            var cantidad_producto = 1; // Puedes cambiar esto según tus necesidades
            var imagen_producto = "<?= $imagen ?>";
            var categoria_producto = "<?= $nombre_categoria ?>";

            // Llamar a la función para enviar el producto al carrito
            enviar_carrito(id_producto, nombre_producto, precio_producto, cantidad_producto,
                imagen_producto, categoria_producto);
        });
    });

    // Función para enviar el producto al carrito mediante AJAX
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

</body>

</html>