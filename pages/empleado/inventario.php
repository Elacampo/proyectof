<?php 

session_start();

if(empty($_SESSION['id'])){
    header("location: ../login.php");
}

if($_SESSION['rol'] == 1){
    header("location: ../admin/admin.php");
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
        href="../../css/admin.css/productosInicio.css?v=<?php echo filemtime('../../css/admin.css/productosInicio.css'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Gestion Inventario</title>
</head>

<body>

    <?php include ('includes/nav-bar.php')?>


    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Gestion de inventario</h1>
            </div>
            <div class="estadisticas-contenedor">

                <div class="opciones">
                    <div class="row">

                        <a href="productos.php" class="box-opc">
                            <i class='bx bx-package bx-lg'></i>
                            <h4>Gestión de Productos</h4>
                        </a>

                        <a href="categoria.php" class="box-opc">
                            <i class='bx bx-category bx-lg'></i>
                            <h4>Consultar Categoria</h4>
                        </a>

                        <a href="espacio.php" class="box-opc">
                            <i class='bx bx-cabinet bx-lg'></i>
                            <h4>Consultar Espacio</h4>
                        </a>

                        <a href="stock.php" class="box-opc">
                            <i class='bx bx-box bx-lg'></i>
                            <h4>Gestión de Stock</h4>
                        </a>

                    </div>

                </div>

            </div>

    </section>

</body>
<script src="../../js/dashboard.js"></script>

</html>