<?php

require '../php/conecta.php';

session_start();
error_reporting(E_ALL & ~E_NOTICE);


if(isset($_SESSION['rol'])) {
    // Redirigir basado en el rol
    if($_SESSION['rol'] == 1){
        header("location: admin/admin.php");
        exit(); // Detener la ejecución después de redirigir
    } elseif($_SESSION['rol'] == 2){
        header("location: empleado/farmaceutico.php");
        exit(); // Detener la ejecución después de redirigir
    } elseif($_SESSION['rol'] == 3){
        header("location: cliente/cliente.php");
        exit(); // Detener la ejecución después de redirigir
    }
} 


      
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
body {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
</style>

<body>



    <nav class="relative px-4 py-4 flex justify-between items-center bg-white">
        <a class="text-3xl font-bold leading-none" href="#">
            <img src="../img/icon.png" alt="" class="h-10">
        </a>
        <div class="lg:hidden">
            <button class="navbar-burger flex items-center text-blue-600 p-3">
                <svg class="block h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Mobile menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </button>
        </div>
        <ul
            class="hidden absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 lg:flex lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6">
            <li><a class="text-sm text-gray-400 hover:text-gray-500" href="../index.php">Home</a></li>
            <li class="text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </li>
            <li><a class="text-sm text-blue-600 font-bold" href="../index.php#catalogo">Catalogo</a></li>
            <li class="text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </li>
            <li><a class="text-sm text-gray-400 hover:text-gray-500" href="../index.php#acerca_de">Acerca de</a></li>
            <li class="text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </li>
            <li><a class="text-sm text-gray-400 hover:text-gray-500" href="../index.php#review">Opiniones</a></li>
            <li class="text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-4 h-4 current-fill"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v0m0 7v0m0 7v0m0-13a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </li>
        </ul>
        <a class="hidden lg:inline-block lg:ml-auto lg:mr-3 py-2 px-6 bg-gray-50 hover:bg-gray-100 text-sm text-gray-900 font-bold  rounded-xl transition duration-200"
            href="login.php">Iniciar sesión</a>
        <a class="hidden lg:inline-block py-2 px-6 bg-blue-500 hover:bg-blue-600 text-sm text-white font-bold rounded-xl transition duration-200"
            href="registro.php">Registrarse</a>
    </nav>

    <div class="navbar-menu relative z-50 hidden">
        <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
        <nav
            class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-white border-r overflow-y-auto">
            <div class="flex items-center mb-8">
                <a class="mr-auto text-3xl font-bold leading-none" href="#">
                    <img src="../img/icon.png" alt="" class="h-10">
                </a>
                <button class="navbar-close">
                    <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div>
                <ul>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded"
                            href="../index.php">Home</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded"
                            href="../index.php#catalogo">Catalogo</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded"
                            href="../index.php#acerca_de">Acerca de</a>
                    </li>
                    <li class="mb-1">
                        <a class="block p-4 text-sm font-semibold text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded"
                            href="../index.php#review">Opiniones</a>
                    </li>

                </ul>
            </div>
            <div class="mt-auto">
                <div class="pt-6">
                    <a class="block px-4 py-3 mb-3 leading-loose text-xs text-center font-semibold leading-none bg-gray-50 hover:bg-gray-100 rounded-xl"
                        href="login.php">Iniciar Sesión</a>
                    <a class="block px-4 py-3 mb-2 leading-loose text-xs text-center text-white font-semibold bg-blue-600 hover:bg-blue-700  rounded-xl"
                        href="registro.php">Registrarse</a>
                </div>
                <p class="my-4 text-xs text-center text-gray-400">
                    <span>Copyright © 2024</span>
                </p>
            </div>
        </nav>
    </div>



    <section class="bg-white py-16 px-8">

        <div class="mx-auto container grid place-items-center grid-cols-1 md:grid-cols-2">
            <div>
                <img src="../img/productos/<?=$imagen?>" alt="pink blazer"
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
                <img src="../img/icon.png" alt="" class="h-10">
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

    <script>
    // Burger menus
    document.addEventListener('DOMContentLoaded', function() {
        // open
        const burger = document.querySelectorAll('.navbar-burger');
        const menu = document.querySelectorAll('.navbar-menu');

        if (burger.length && menu.length) {
            for (var i = 0; i < burger.length; i++) {
                burger[i].addEventListener('click', function() {
                    for (var j = 0; j < menu.length; j++) {
                        menu[j].classList.toggle('hidden');
                    }
                });
            }
        }

        // close
        const close = document.querySelectorAll('.navbar-close');
        const backdrop = document.querySelectorAll('.navbar-backdrop');

        if (close.length) {
            for (var i = 0; i < close.length; i++) {
                close[i].addEventListener('click', function() {
                    for (var j = 0; j < menu.length; j++) {
                        menu[j].classList.toggle('hidden');
                    }
                });
            }
        }

        if (backdrop.length) {
            for (var i = 0; i < backdrop.length; i++) {
                backdrop[i].addEventListener('click', function() {
                    for (var j = 0; j < menu.length; j++) {
                        menu[j].classList.toggle('hidden');
                    }
                });
            }
        }
    });
    </script>


    <script>
    // Función para agregar un producto al carrito
    $(document).ready(function() {
        $("#btn-add-carrito").on("click", function() {
            // Mostrar una alerta indicando que el usuario debe iniciar sesión para agregar productos al carrito
            Swal.fire({
                icon: 'info',
                title: 'Inicia sesión',
                text: 'Debes iniciar sesión para poder agregar productos al carrito.',
                showConfirmButton: false,
                timer: 3000 // La alerta se cerrará automáticamente después de 3 segundos
            });
        });
    });
    </script>

</body>

</html>