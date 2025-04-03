<?php

session_start();
error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    require '../../php/conecta.php';



    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <?php include('includes/nav_bar.php');?>

    <!-- component -->
    <div class="min-h-screen p-6 bg-gray-100 flex items-center justify-center">
        <div class="container max-w-screen-lg mx-auto">

            <div>
                <h2 class="font-semibold text-xl text-gray-600">Mi cuenta</h2>

                <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">

                    <?php
                            include('../../php/actualizar_cliente.php');
                        ?>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" onsubmit="validarFormulario(event)">

                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                            <div class="text-gray-600">
                                <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Detalles de la
                                    cuenta</h2>
                                <p>Tus datos personales</p>
                            </div>

                            <div class="lg:col-span-2">
                                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5">
                                    <div class="md:col-span-2">
                                        <label for="first_name">Nombre</label>
                                        <input type="text" name="first_name" id="first_name"
                                            class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                            value="<?= ucfirst($nombre_usuario)?>" />
                                    </div>

                                    <div class="md:col-span-3">
                                        <label for="last_name">Apellido</label>
                                        <input type="text" name="last_name" id="last_name"
                                            class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                            value="<?= ucfirst($apellido_usuario)?>" />
                                    </div>

                                    <div class="md:col-span-5">
                                        <label for="email">Correo Electronico</label>
                                        <input type="text" name="email" id="email"
                                            class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                            value="<?= $correo_usuario?>" />
                                    </div>

                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">
                                            <input type="submit" name="btnActualizar" value="Actualizar"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:cursor-pointer" />
                                            <input type="hidden" name="id" value="<?= $id_usuario?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                        
                        ?>
                </div>
            </div>

            <div>
                <h2 class="font-semibold text-xl text-gray-600">Cambiar contraseña</h2>

                <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" onsubmit="validarPassword(event)">

                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                            <div class="text-gray-600">

                                <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Requisitos de
                                    Contraseña:
                                </h2>
                                <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                                    <li>
                                        Debe contener al menos un carácter en minúscula y mayúscula.
                                    </li>
                                    <li>
                                        Debe contener al menos un dígito.
                                    </li>
                                    <li>
                                        Debe contener al menos uno de los caracteres especiales: @, $, !, %, *, ?, o
                                        &amp;.
                                    </li>
                                    <li>
                                        Debe tener una longitud mínima de 8 caracteres.
                                    </li>
                                </ul>
                            </div>

                            <div class="lg:col-span-2">
                                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5">

                                    <div class="md:col-span-3">
                                        <label for="new_password">Contraseña nueva</label>
                                        <input type="password" name="new_password" id="new_password"
                                            class="h-10 border mt-1 rounded px-4 w-full bg-gray-50" value="" />
                                    </div>

                                    <div class="md:col-span-3">
                                        <label for="confirm_password">Confirmar contraseña</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            class="h-10 border mt-1 rounded px-4 w-full bg-gray-50" value="" />
                                    </div>

                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">

                                            <input type="submit" name="btnPassword" value="Cambiar contraseña"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:cursor-pointer" />
                                            <input type="hidden" name="id" value="<?= $id_usuario?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <footer class="text-gray-600 body-font">
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
</body>

<script>
const expresiones = {
    nombreUsuario: /^[a-zA-ZÀ-ÿ\s]{1,255}$/, // Letras y espacios, pueden llevar acentos.
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
};

function validarPassword(event) {
    var new_password = document.querySelector('input[name="new_password"]').value;
    var confirm_password = document.querySelector('input[name="confirm_password"]').value;

    if (new_password === '' || confirm_password === '') {
        mostrarError('Todos los campos de la contraseña son obligatorios. Por favor, completa todos los campos.')
        event.preventDefault();
        return false;
    }

    if (!expresiones.password.test(new_password)) {
        mostrarError('La contraseña no cumple con los requisitos.');
        event.preventDefault();
        return false;
    }

    if (new_password !== confirm_password) {
        mostrarError('Las contraseñas no coinciden. Por favor, verifica que las contraseñas coincidan.');
        event.preventDefault();
        return false;
    }
}


function validarFormulario(event) {
    var nombre = document.querySelector('input[name="first_name"]').value.trim();
    var apellido = document.querySelector('input[name="last_name"]').value.trim();
    var correo = document.querySelector('input[name="email"]').value.trim();

    // Verificar si algún campo está vacío
    if (nombre === '' || apellido === '' || correo === '') {
        mostrarError('Todos los campos son obligatorios. Por favor, completa todos los campos.');
        event.preventDefault();
        return false;
    }

    // Verificar el formato del nombre
    if (!expresiones.nombreUsuario.test(nombre)) {
        mostrarError('El nombre no puede contener números y debe ser válido.');
        event.preventDefault();
        return false;
    }

    // Verificar el formato del apellido
    if (!expresiones.nombreUsuario.test(apellido)) {
        mostrarError('El apellido no puede contener números y debe ser válido.');
        event.preventDefault();
        return false;
    }

    // Verificar el formato del correo electrónico
    if (!expresiones.correo.test(correo)) {
        mostrarError('Por favor, ingresa un correo electrónico válido.');
        event.preventDefault();
        return false;
    }

    return true;
}

function mostrarError(mensaje) {
    Swal.fire({
        title: 'Error!',
        text: mensaje,
        icon: 'error'
    });
}
</script>

</html>