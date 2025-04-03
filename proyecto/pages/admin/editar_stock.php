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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Editar Stock</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Stock</h1>
                    <h6>Editar Stock</h2>

            </div>

            <div class="information-container">

                <?php include('../../php/editar_stock_controlador.php'); ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="provider-form"
                    onsubmit="validarFormulario(event)">


                    <div class="row-name">
                        <div class="nombre">
                            <label for="">Stock Actual</label>
                            <input type="text" name="stock_actual" value="<?php echo $stock_actual;?>">
                        </div>

                        <div class="apellido">
                            <label for="">Producto</label>
                            <input type="text" name="nombre_producto" value="<?php echo $producto;?>" readonly>
                        </div>
                    </div>

                    <div class="row-name">
                        <div class="nombre">

                            <label for="area">Area</label>
                            <input type="text" name="area" id="" value="<?php echo $area?>" readonly>
                        </div>

                        <div class="apellido">
                            <label for="cod_bodega">Cod. Bodega</label>
                            <input type="text" name="cod_bodega" id="cod_bodega" value="<?php echo $row['ubicacion'];?>"
                                readonly>
                        </div>

                    </div>

                    <div class="buttons">
                        <input type="submit" value="Actualizar" name="btnEnviar">
                        <a href="stock.php" class="btn-volver">Volver</a>
                    </div>

                    <input type="hidden" name="producto_stock" value="<?php echo $row['producto'];?>">
                </form>


            </div>
        </div>
        </div>

    </section>

</body>
<script src="../../js/dashboard.js"></script>

<script>
const areaSelect = document.getElementById('areaSelect');
const codBodegaInput = document.getElementById('cod_bodega');

areaSelect.addEventListener('change', function() {
    const selectedValue = areaSelect.value;
    codBodegaInput.value = selectedValue;
})


const expresiones = {
    stockNum_verificar: /^\d{1,12}$/
};

function validarFormulario(event) {
    const stockNum = document.querySelector('input[name="stock_actual"]').value.trim();

    // Verificar si algún campo está vacío
    if (stockNum === '') {
        Swal.fire({
            title: 'Campo vacío!',
            text: 'El campos es obligatorio. Por favor, completa el campo del stock.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    if (!expresiones.stockNum_verificar.test(stockNum)) {
        mostrarError('El stock actual debe ser un número entero');
        event.preventDefault();
        return false;
    }
}

function mostrarError(mensaje) {
    Swal.fire({
        title: 'Error!',
        text: mensaje,
        icon: 'error'
    });
}
</script>


</script>

</html>