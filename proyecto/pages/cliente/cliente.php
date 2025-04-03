<?php


require '../../php/conecta.php';
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

if (!isset($_SESSION['cantidad_productos_carrito'])) {
    $_SESSION['cantidad_productos_carrito'] = 0;
}

$id_usuario = $_SESSION['id'];

//Consulta productos (4)
$productos_consulta = "SELECT nombre_producto ,precio_producto,imagen_producto FROM producto LIMIT 4";
$resultado_p = mysqli_query($conectar, $productos_consulta);
$productos_fetch = mysqli_fetch_all($resultado_p, MYSQLI_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous"
        defer></script>
    <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script>
    var cont = 0;
    var xx; // Declarar la variable xx fuera de la función loopSlider()

    function loopSlider() {
        xx = setInterval(function() { // Asignar el setInterval a la variable xx
            switch (cont) {
                case 0: {
                    $("#slider-1").fadeOut(400);
                    $("#slider-2").delay(400).fadeIn(400);
                    $("#sButton1").removeClass("bg-purple-800");
                    $("#sButton2").addClass("bg-purple-800");
                    cont = 1;
                    break;
                }
                case 1: {
                    $("#slider-2").fadeOut(400);
                    $("#slider-1").delay(400).fadeIn(400);
                    $("#sButton2").removeClass("bg-purple-800");
                    $("#sButton1").addClass("bg-purple-800");
                    cont = 0;
                    break;
                }
            }
        }, 8000);
    }

    function reinitLoop(time) {
        clearInterval(xx);
        setTimeout(loopSlider, time); // Llamar a loopSlider sin paréntesis
    }

    function sliderButton1() {
        $("#slider-2").fadeOut(400);
        $("#slider-1").delay(400).fadeIn(400);
        $("#sButton2").removeClass("bg-purple-800");
        $("#sButton1").addClass("bg-purple-800");
        reinitLoop(4000);
        cont = 0;
    }

    function sliderButton2() {
        $("#slider-1").fadeOut(400);
        $("#slider-2").delay(400).fadeIn(400);
        $("#sButton1").removeClass("bg-purple-800");
        $("#sButton2").addClass("bg-purple-800");
        reinitLoop(4000);
        cont = 1;
    }

    $(window).ready(function() {
        $("#slider-2").hide();
        $("#sButton1").addClass("bg-purple-800");
        loopSlider();
    });
    </script>

</head>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    font-family: 'Poppins', sans-serif;
}
</style>



<body>





    <div class="sliderAx h-auto ">
        <div id="slider-1" class="container mx-auto">
            <div class="bg-cover bg-center  h-auto text-white py-24 px-10 object-fill "
                style="background-image: url(https://us.123rf.com/450wm/tallula/tallula1905/tallula190500253/122180827-comprimidos-blancos-esparcidos-sobre-un-fondo-rosa-de-cerca.jpg?ver=6);background-position:left">
                <div class="md:w-1/2">
                    <p class="font-bold text-sm uppercase text-black">Descubre nuestros servicios</p>
                    <p class="text-3xl font-bold text-black">Servicios de bienestar integral</p>
                    <p class="text-2xl mb-10 leading-none text-black">Encuentra información detallada sobre el uso
                        adecuado de
                        medicamentos y recomendaciones para la prevención de enfermedades.</p>
                </div>
            </div> <!-- container -->
            <br>
        </div>

        <div id="slider-2" class="container mx-auto">
            <div class="bg-cover bg-opacity-0 bg-top  h-auto text-white py-24 px-10 object-fill md:bg-position-center"
                style="background-image: url(https://mykredit.es/assets/contact-us-eb232600.svg);background-position:center">

                <p class="font-bold text-sm uppercase text-black">Contacto</p>
                <p class="text-3xl font-bold text-black">¿Tienes preguntas o comentarios?</p>
                <p class="text-2xl mb-10 leading-none text-black">Estamos aquí para ayudarte, no dudes en comunicarte
                    con nosotros.
                </p>
                <a href="#contact"
                    class="bg-purple-800 py-4 px-8 text-white font-bold uppercase text-xs rounded hover:bg-gray-200 hover:text-gray-800">Contactanos</a>

            </div> <!-- container -->
            <br>
        </div>
    </div>
    <div class="flex justify-between w-12 mx-auto pb-2">
        <button id="sButton1" onclick="sliderButton1()" class="bg-purple-400 rounded-full w-4 pb-2"></button>
        <button id="sButton2" onclick="sliderButton2() " class="bg-purple-400 rounded-full w-4 p-2"></button>
    </div>






    <section class="bg-blue-50">
        <div class="relative items-center w-full px-5 py-12 mx-auto md:px-12 lg:px-24 max-w-7xl">
            <div class=" grid w-full grid-cols-1 gap-12 mx-auto lg:grid-cols-3">
                <div class="p-6">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto mb-5 text-blue-600 rounded-full bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 icon icon-tabler icon-tabler-aperture"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="3.6" y1="15" x2="14.15" y2="15"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(72 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(144 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(216 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(288 12 12)"></line>
                        </svg>
                    </div>
                    <h1
                        class="mx-auto mb-8 text-2xl font-semibold leading-none tracking-tighter text-neutral-600 lg:text-3xl">
                        Bienvenido a nuestra sección de Farmacia</h1>
                    <p class="mx-auto text-base leading-relaxed text-gray-500">Aquí encontrarás información relevante
                        sobre productos farmacéuticos, servicios y promociones especiales para nuestros clientes.</p>
                </div>
                <div class="p-6">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto mb-5 text-blue-600 rounded-full bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 icon icon-tabler icon-tabler-aperture"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="3.6" y1="15" x2="14.15" y2="15"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(72 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(144 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(216 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(288 12 12)"></line>
                        </svg>
                    </div>
                    <h1
                        class="mx-auto mb-8 text-2xl font-semibold leading-none tracking-tighter text-neutral-600 lg:text-3xl">
                        Información al Escanear.</h1>
                    <p class="mx-auto text-base leading-relaxed text-gray-500">Todos nuestros productos cuentan con
                        códigos QR que, al ser escaneados,
                        proporcionan información importante como instrucciones de uso, advertencias, fecha de caducidad
                        y más.</p>
                </div>
                <div class="p-6">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto mb-5 text-blue-600 rounded-full bg-blue-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 icon icon-tabler icon-tabler-aperture"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="3.6" y1="15" x2="14.15" y2="15"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(72 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(144 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(216 12 12)"></line>
                            <line x1="3.6" y1="15" x2="14.15" y2="15" transform="rotate(288 12 12)"></line>
                        </svg>
                    </div>
                    <h1
                        class="mx-auto mb-8 text-2xl font-semibold leading-none tracking-tighter text-neutral-600 lg:text-3xl">
                        Bienestar Familiar</h1>
                    <p class="mx-auto text-base leading-relaxed text-gray-500">Cuida de la salud de toda tu familia con
                        nuestra amplia gama de productos para adultos y niños. Encuentra todo lo que necesitas para el
                        bienestar de tus seres queridos en un solo lugar.</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <header class="text-center">
                <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">¡Explora Nuestras Categorias!</h2>

                <p class="mx-auto mt-4 max-w-md text-gray-500">
                    Aquí encontrarás una amplia variedad de productos para tu salud y bienestar. Desde medicamentos
                    hasta productos de cuidado personal, ¡todo lo que necesitas está a solo unos clics de distancia!
                </p>
            </header>

            <ul class="mt-8 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <li>
                    <a href="catalogo.php" class="group relative block">

                        <img src="https://2.bp.blogspot.com/-QLnwLMtHu5Q/V3AybKR6gFI/AAAAAAAAS28/zBcLgZmcYQg0ZyUI0agjrzZbckH3AtjKQCLcB/s1600/neceser-verano-farmacia.png"
                            alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />


                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Cuidados de la Piel
                            </span>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="catalogo.php" class="group relative block">
                        <img src="https://plustatic.com/4821/conversions/mejores-medicamentos-analgesicos-large.jpg"
                            alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />

                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Analgesicos
                            </span>
                        </div>
                    </a>
                </li>

                <li class="lg:col-span-2 lg:col-start-2 lg:row-span-2 lg:row-start-1">
                    <a href="catalogo.php" class="group relative block">
                        <img src="https://cdn.pixabay.com/photo/2020/05/23/19/33/vitamins-5211139_1280.jpg" alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />

                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Vitaminas y Suplementos
                            </span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-blue-50 py-12 text-gray-700 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-md text-center">
                <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">Quizás te interes</h2>
                <p class="mx-auto mt-4 max-w-md text-gray-500">Descubre nuestra gama de productos farmacéuticos de alta
                    calidad. ¡Mejora tu calidad de vida hoy mismo!</p>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-6 lg:mt-16 lg:grid-cols-4 lg:gap-4">

                <?php 
                $sql_consulta = "SELECT p.id, p.imagen_producto ,p.nombre_producto, p.categoria, p.fecha_ven,  p.precio_producto
                FROM producto p
                INNER JOIN stock s ON p.id = s.producto
                WHERE p.fecha_ven >= CURDATE() AND s.stock_actual > 0
                LIMIT 4;";

                $sentencia_productos = $conectar ->prepare($sql_consulta);
                $sentencia_productos->execute();
                $resultado_pr = $sentencia_productos->get_result(); // Get the result set
                $resultado_articulos = $resultado_pr->fetch_all(MYSQLI_ASSOC);
                
                foreach($resultado_articulos as $resultado){

                    if(empty($resultado['imagen_producto'])){
                        $imagen = 'no-imagen.jpg';
                    }else{
                        $imagen = $resultado['imagen_producto'];
                    }

                    $precio_formateado = number_format($resultado['precio_producto'], 0, ',', '.');

                    $categoria_id = $resultado['categoria'];
                    $sql_categoria = $conectar->prepare("SELECT nombre,id_cat FROM categoria WHERE id_cat = ?");
                    $sql_categoria->bind_param("i", $categoria_id);
                    $sql_categoria->execute();
                    $resultCategoria = $sql_categoria->get_result();
                    $categoria_resultado = $resultCategoria->fetch_assoc();?>

                <article class="relative">
                    <div class="aspect-square overflow-hidden">
                        <a href="ver_producto.php?id=<?= $resultado['id']?>">
                            <img class="group-hover:scale-125 h-full w-full object-cover transition-all duration-300"
                                src="../../img/productos/<?php echo $imagen;?>" alt="" />
                        </a>
                    </div>
                    <div class="mt-4 flex items-start justify-between">
                        <div class="">
                            <h3 class="text-xs font-semibold sm:text-sm md:text-base">
                                <a href="ver_producto.php?id=<?= $resultado['id']?>" title="" class="cursor-pointer">
                                    <?php 
                                        echo $resultado['nombre_producto'];                     
                                    ?>
                                    <span class="absolute" aria-hidden="true"></span>
                                </a>
                            </h3>

                        </div>

                        <div class="text-right">
                            <p class="text-xs font-normal sm:text-sm md:text-base">
                                <?php echo $precio_formateado . ' COP';?></p>
                        </div>
                    </div>
                </article>

                <?php } ?>

            </div>
        </div>
    </section>

    <section class="bg-white" id="contact">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
            <div class="max-w-2xl lg:max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Visita nuestra farmacia!</h2>
                <p class="mt-4 text-lg text-gray-500">Te invitamos a conocer nuestra farmacia y descubrir cómo podemos ayudarte a cuidar de tu salud y bienestar.</p>
            </div>
            <div class="mt-16 lg:mt-20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="rounded-lg overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15666.764630000325!2d-74.7876986!3d10.986668!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8ef5cff2b82b8815%3A0x4e7910ae3b78e831!2sUnibarranquilla!5e0!3m2!1ses-419!2sco!4v1712930930790!5m2!1ses-419!2sco"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div>
                        <div class="max-w-full mx-auto rounded-lg overflow-hidden">
                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900"> Nuestra Dirección</h3>
                                <p class="mt-1 text-gray-600">53, Cra. 5 Sur, Nte. Centro Historico
                                    Barranquilla, Atlántico
                                    Colombia</p>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Horas</h3>
                                <p class="mt-1 text-gray-600">Lunes - Viernes: 9am - 9pm</p>
                                <p class="mt-1 text-gray-600">Sábado: 9am - 6pm</p>
                                <p class="mt-1 text-gray-600">Domingo: Cerrado</p>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Contacto</h3>
                                <p class="mt-1 text-gray-600">Correo: farmaciaBQ@gmail.com</p>
                                <p class="mt-1 text-gray-600">Celular: +57 300 3958741</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php')?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>


</body>

</html>