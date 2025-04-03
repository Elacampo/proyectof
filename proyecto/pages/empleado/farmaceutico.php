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

require '../../php/conecta.php';

//Consulta para saber cantidad de stock
$sql_stock = "SELECT COUNT(*) AS cantidad_stock FROM stock";
$resultado = mysqli_query($conectar,$sql_stock);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_stock = $fila['cantidad_stock'];

//Consulta para saber cantidad de stock bajo
$stock_critico = 10;
$sql_stock = "SELECT COUNT(*) AS cantidad_stock_critico FROM stock WHERE stock_actual <= $stock_critico";
$resultado = mysqli_query($conectar,$sql_stock);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_stock_critico = $fila['cantidad_stock_critico'];

//Cantida de productos fuera de stock
$sqlFueraStock = "SELECT COUNT(p.id) AS cantidad_fuera_stock
FROM producto p
LEFT JOIN stock s ON p.id = s.producto
WHERE s.stock_actual = 0 OR s.stock_actual IS NULL";
$resultado = mysqli_query($conectar, $sqlFueraStock);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_out_stock = $fila ['cantidad_fuera_stock'];

//Cantidad de productos proximos a vencer
$fecha_limite = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));
$sql_produc_prox = "SELECT COUNT(*) AS cantidad_proximos_a_vencer FROM producto WHERE fecha_ven BETWEEN CURDATE() AND '$fecha_limite'";
$resultado = mysqli_query($conectar,$sql_produc_prox);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_proximos_a_vencer = $fila['cantidad_proximos_a_vencer'];

//Cantidad de productos vencidos
$sql_productos_vencidos = "SELECT COUNT(*) AS cantidad_vencidos FROM producto WHERE fecha_ven <= CURDATE()";
$resultado = $resultado = mysqli_query($conectar,$sql_productos_vencidos);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_productos_vencidos = $fila['cantidad_vencidos'];

//Ventas del dia
$hoy = date("Y-m-d");
$sqlVentas = "SELECT COUNT(*) AS total_ventas FROM factura WHERE estado_factura = 'Pagado' 
AND DATE(fecha_emision) = '$hoy' AND '$hoy' <= DATE(fecha_limite_validez)";
$resultado = mysqli_query($conectar,$sqlVentas);
$fila = mysqli_fetch_assoc($resultado);
$cantidad_ventas_total = $fila ['total_ventas'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo filemtime('../../css/dashboard.css'); ?>">
    <link rel="stylesheet"
        href="../../css/farma.css/inicio.css?v=<?php echo filemtime('../../css/farma.css/inicio.css'); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Farmaceutico</title>
</head>

<body>

    <?php include ('includes/nav-bar.php')?>
    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Dashboard</h1>
                    <h6>Home</h2>
            </div>
            <div class="estadisticas-contenedor">
                <div class="row">
                    <div class="cajaE">
                        <h4>Productos en Stock</h4>
                        <h4><?php echo $cantidad_stock; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Productos Stock Critico</h4>
                        <h4><?php echo $cantidad_stock_critico; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Productos Agotados</h4>
                        <h4><?php echo $cantidad_productos_out_stock; ?></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="cajaE">
                        <h4>Productos Próximos a Expirar</h4>
                        <h4><?php echo $cantidad_productos_proximos_a_vencer; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Productos Expirados</h4>
                        <h4><?php echo $cantidad_productos_vencidos; ?></h4>
                    </div>
                    <div class="cajaE">
                        <h4>Ventas del día</h4>
                        <h4><?= $cantidad_ventas_total?></h4>
                    </div>
                </div>
            </div>

            <div class="opciones">

                <div class="row">

                    <a href="agregar_producto.php" class="box-opc">
                        <i class='bx bx-plus-medical bx-lg'></i>
                        <h4>Añadir medicamento</h4>
                    </a>

             
                        <a href="clientes.php" class="box-opc">
                            <i class='bx bxs-user-detail bx-lg'></i>
                            <h4>Ver Clientes</h4>
                        </a>



                        <a href="facturas.php" class="box-opc">
                            <i class='bx bxs-spreadsheet bx-lg'></i>
                            <h4>Ver Facturas</h4>
                        </a>

                   
                        <a href="stock.php" class="box-opc">
                            <i class='bx bxs-box bx-lg'></i>
                            <h4>Ver stock</h4>
                        </a>
                    




                </div>

                <div class="row">

                </div>

            </div>

        </div>



    </section>

</body>
<script src="../../js/dashboard.js"></script>

</html>