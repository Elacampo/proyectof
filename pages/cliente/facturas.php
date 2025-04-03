<?php

require '../../php/conecta.php';

session_start();

error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id'];


    $sql_factura = "SELECT id_factura, fecha_emision, fecha_limite_validez, estado_factura 
    FROM factura WHERE id_cliente = $id_usuario";
    $sentencia_factura = $conectar ->prepare($sql_factura);
    $sentencia_factura -> execute();
    $resultado_factura = $sentencia_factura ->get_result();
    $factura_info = $resultado_factura ->fetch_all(MYSQLI_ASSOC);
    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis facturas</title>
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

    <div class="container mx-auto mt-8 max-w-screen-xl  ">
        <!-- Agregamos la clase max-w-screen-lg -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Num. Factura
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha Emision
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha Vencimiento
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php if(empty($factura_info)){?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay facturas disponibles.
                        </td>
                    </tr>
                    <?php }else{?>

                        <?php foreach($factura_info as $filas){
                            $hoy = date('Y-m-d');
                            $fecha_emision = date('d/m/y', strtotime($filas['fecha_emision']));
                            $fecha_ven = date('d/m/y', strtotime($filas['fecha_limite_validez']));
                        ?>
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?= $filas['id_factura']?>
                            </th>
                            <td class="px-6 py-4">
                                <?= $fecha_emision?>
                            </td>
                            <td class="px-6 py-4">
                                <?= $fecha_ven?>
                            </td>
                            <td class="px-6 py-4">
                                <?= $filas['estado_factura']?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex">
                                    <a href="ver_factura.php?id_factura=<?= $filas['id_factura']?>"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-4">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <?php } ?>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

    <?php include('includes/footer.php')?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>