<?php 

session_start();

if(empty($_SESSION['id'])){
    header("location: ../login.php");
}

if($_SESSION['rol'] == 2){
    header("location: ../empleado/farmaceutico.php");
}

if($_SESSION['rol'] == 3){
    header("location: ../cliente/cliente.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo filemtime('../../css/dashboard.css'); ?>">
    <link rel="stylesheet"
        href="../../css/admin.css/adminInicio.css?v=<?php echo filemtime('../../css/admin.css/adminInicio.css'); ?>">
    <link rel="stylesheet"
        href="../../css/admin.css/infoUsuario.css?v=<?php echo filemtime('../../css/admin.css/infoUsuario.css'); ?>">
    <link rel="stylesheet"
        href="../../css/admin.css/infoProveedor.css?v=<?php echo filemtime('../../css/admin.css/infoProveedor.css'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css/infoProducto.css">
    <link rel="stylesheet" href="../../css/admin.css/admin_user.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Ver producto</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">

                <div class="info">
                    <h5>Producto</h1>
                        <h6>Ver Producto</h2>
                </div>

                <div class="volver">
                    <a href="productos.php" id="btnVolver">Volver</a>
                </div>

            </div>



            <div class="information-container">
                <?php include('../../php/ver_producto_controlador.php'); ?>


                <div class="info-izquierda">

                    <div class="info_card">

                        <imagen class="imagen_info">
                            <h1><?php echo $nombre; ?></h1>
                            <figure>
                                <img src="../../img/productos/<?php echo $imagen?>" alt="">
                            </figure>
                        </imagen>

                        <div class="descripcion">
                            <h2>Detalles</h2>
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
                    </div>


                </div>

                <div class="info-derecha">

                    <div class="fila">

                        <div class="categoria">
                            <label for="categoria">Categoria</label>
                            <input type="text" readonly value="<?php echo $categoria; ?>">
                        </div>

                        <div class="unidad_medida">
                            <label for="Medida">Unidad de medida</label>
                            <input type="text" readonly value="<?php echo $unidad_medida; ?>">
                        </div>

                        <div class="precio">
                            <label for="precio">Precio</label>
                            <input type="text" readonly value="$ <?php echo $precio; ?>">
                        </div>
                    </div>

                    <div class="fila">
                        <div class="cod_bodega">
                            <label for="cod_bodega">Cod. Bodega</label>
                            <input type="text" readonly value="<?php echo $cod_bodega; ?>">
                        </div>

                        <div class="area">
                            <label for="area">Area</label>
                            <?php if ($activarArea) : ?>
                            <h4 class="noStockAsignado" style="padding:10px; margin-right:20px">No asignado</h4>
                            <?php else : ?>
                            <input type="text" readonly value="<?php echo $area; ?>">
                            <?php endif; ?>
                        </div>

                        <div class="stock_actual">
                            <label for="stock_actual">Stock actual</label>

                            <?php if ($activarH4) : ?>
                            <h4 class="noStockAsignado" style="padding:10px">No asignado</h4>
                            <?php ; elseif ($stock_actual == 0) : ?>
                            <h4 class="fueraStock">Agotado</h4>
                            <?php else : ?>
                            <input type="text" readonly value="<?php echo $stock_actual; ?>">
                            <?php endif; ?>

                        </div>

                    </div>

                    <div class="fila">

                        <div class="fecha_ingreso">
                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                            <input type="text" readonly value="<?php echo $fecha_ingreso_formateada; ?>">
                        </div>

                        <div class="fecha_ven">
                            <label for="fecha_ven">Fecha de vencimiento</label>
                            <div class="alerta_ven" style="display: flex;">
                                <input type="text" readonly value="<?php echo $fecha_ven_formateada; ?>">
                                <div class="vencimiento" style="display: none;">
                                    <i class='bx bx-error-circle bx-sm'></i>
                                    <h5>Expirado</h5>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="fila">

                        <div class="proveedor">
                            <label for="proveedor">Proveedor</label>
                            <input type="text" style="width: 130%" readonly value="<?php echo $proveedor; ?>">
                        </div>

                    </div>
                    <div class="qr_code">
                        <label for="qr_code">Codigo QR</label>

                        <figure class="codigo_qr">
                            <?php if (!empty($row['qr_imagen'])): ?>
                            <img src="../../img/codigos_qr/<?php echo $row['qr_imagen']?>" alt="Código QR generado">
                            <?php else: ?>
                            <img src="../../img/codigos_qr/no-qr.png" alt="No hay código QR generado" style="width:30%">
                            <?php endif; ?>
                        </figure>

                    </div>

                </div>
            </div>
        </div>
        </div>
    </section>

</body>
<script>
document.getElementById('btnVolver').addEventListener('click', function(event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
    history.back(); // Volver a la página anterior en el historial del navegador
});

document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos relevantes
    const fechaVencimientoInput = document.querySelector('.fecha_ven input[type="text"]');
    const vencimientoDiv = document.querySelector('.fecha_ven .vencimiento');

    // Función para verificar si la fecha está expirada
    function verificarExpiracion() {
        const fechaVencimiento = new Date(fechaVencimientoInput.value);
        const hoy = new Date();

        if (fechaVencimiento < hoy) {
            vencimientoDiv.style.display = 'flex'; // Mostrar mensaje de expirado
        } else {
            vencimientoDiv.style.display = 'none'; // Ocultar mensaje de expirado
        }
    }

    // Verificar la expiración al cargar la página
    verificarExpiracion();

    // Verificar la expiración al cambiar la fecha de vencimiento
    fechaVencimientoInput.addEventListener('change', verificarExpiracion);
});
</script>

<script src="../../js/dashboard.js"></script>



</html>