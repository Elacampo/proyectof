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
        href="../../css/admin.css/productosInicio.css?v=<?php echo filemtime('../../css/admin.css/productosInicio.css'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Reporte</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Reporte de productos</h1>
            </div>
            <div class="estadisticas-contenedor">

                <div class="opciones">
                    <div class="row">

                        <a href="proximos_vencimientos.php" class="box-opc">
                            <i class='bx bxs-calendar-exclamation bx-lg'></i>
                            <h4>Proximo a Expirar / Expirados</h4>
                        </a>

                        <a href="stock-info.php" class="box-opc">
                            <i class='bx bx-x-circle bx-lg'></i>
                            <h4>Fuera de stock</h4>
                        </a>

                    </div>

                </div>

            </div>

    </section>

</body>
<script src="../../js/dashboard.js"></script>

</html>