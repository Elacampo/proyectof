<?php

require '../../php/conecta.php';

session_start();

if(empty($_SESSION['id'])){
    header("location: ../../login.php");
}

if($_SESSION['rol'] == 1){
    header("location: ../admin/admin.php");
}

if($_SESSION['rol'] == 2){
    header("location: ../empleado/farmaceutico.php");
}

$id_usuario = $_SESSION['id'];
//Consulta iniciales
$iniciales_consulta = "SELECT CONCAT(SUBSTRING(nombre,1,1), SUBSTRING(apellido,1,1)) AS iniciales FROM usuarios WHERE id = $id_usuario";
$resultado_i = mysqli_query($conectar, $iniciales_consulta);
$inicial_fetch = mysqli_fetch_assoc($resultado_i);
$inicial = $inicial_fetch['iniciales'];

//Consulta correo
$email_consulta = "SELECT email FROM usuarios WHERE id = $id_usuario";
$resultado_c = mysqli_query($conectar, $email_consulta);
$email_fetch = mysqli_fetch_assoc($resultado_c);
$correo = $email_fetch['email'];


 
?>


    <script src="https://cdn.tailwindcss.com"></script>

    <nav class="bg-white border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="cliente.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../img/icon.png" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Farma</span>
            </a>

            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

                <?php include ('carrito.php')?>
                <button type="button"
                    class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <div
                        class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full">
                        <span class="font-medium text-gray-600 dark:text-gray-300"><?php echo $inicial;?></span>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="user-dropdown">
                    <div class="px-4 py-3">

                        <span
                            class="block text-sm text-gray-900 dark:text-white"><?= ucfirst($_SESSION['nombre'])," ", ucfirst($_SESSION['apellido']);?></span>
                        <span
                            class="block text-sm  text-gray-500 truncate dark:text-gray-400"><?php echo $correo; ?></span>
                    </div>

                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="mi-cuenta.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Mi
                                cuenta</a>
                        </li>
                        <li>
                            <a href="facturas.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Mis
                                facturas</a>
                        </li>
                        <li>
                            <a href="../../php/controladorCerrar.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Cerrar
                                Sesión</a>
                        </li>
                        
                    </ul>

                </div>
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul
                    class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="cliente.php"
                            class="<?php
                        echo (basename($_SERVER['PHP_SELF']) == 'cliente.php') ? 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500' 
                        : 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700' ;?>">Home</a>
                    </li>


                    <li>
                        <a href="catalogo.php"
                            class="<?php
                        echo (basename($_SERVER['PHP_SELF']) == 'catalogo.php') ? 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500' 
                        : 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700' ;?>">Catalogo</a>
                    </li>
                    <li>
                        <a href="faq.php"
                            class="<?php
                        echo (basename($_SERVER['PHP_SELF']) == 'faq.php') ? 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500' 
                        : 'block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700';?>">FAQ</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

