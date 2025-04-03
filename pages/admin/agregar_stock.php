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
    <title>Agregar Stock</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>


    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Stock</h1>
                    <h6>Agregar Stock</h2>

            </div>

            <div class="information-container">

                <h1 id="mensaje" style="margin-bottom:20px; display:none">Seleccione un producto</h1>

                <?php include('../../php/agregar_stock_controlador.php'); ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="provider-form"
                    onsubmit="validarFormulario(event)" id="stockForm">


                    <div class="row-name">

                        <div class="apellido">
                            <label for="">Producto</label>
                            <select name="nombre_producto" id="productoSelect">
                                <option value="" selected disabled>Seleccione aqui</option>
                                <?php foreach ($resultProductos as $opc_productos): ?>
                                <option value="<?php echo $opc_productos['id']?>"
                                    data-cod_bodega="<?php echo $opc_productos['ubicacion']?>"
                                    data-area="<?php echo $opc_productos['area']?>">
                                    <?php echo $opc_productos['nombre_producto'] ?>
                                </option>
                                <?php endforeach ?>
                            </select>

                        </div>


                        <div class="nombre">
                            <label for="">Stock Actual</label>
                            <input type="text" name="stock_actual" value="">
                        </div>

                    </div>

                    <div class="row-name">
                        <div class="nombre">
                            <label for="area">Area</label>

                            <select name="area" id="areaSelect">
                                <option value="" disabled selected>Seleccione Aqui</option>
                                <?php foreach ($result_espacio as $opc_espacio): ?>
                                <option value="<?php echo $opc_espacio['id_bodega'] ?>">
                                    <?php echo $opc_espacio['area'] ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="apellido">
                            <label for="cod_bodega">Cod. Bodega</label>
                            <input type="text" name="cod_bodega" id="cod_bodega" readonly>
                        </div>

                    </div>

                    <div class="buttons">
                        <input type="submit" value="Actualizar" name="btnEnviar" id="enviar">
                        <a href="stock.php" class="btn-volver">Volver</a>
                    </div>

                    <input type="hidden" name="producto_stock">
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


const selectProductos = document.querySelector('select[name="nombre_producto"]');
const inputsAfectados = document.querySelectorAll('input,select[name="area"]');

function toggleInputs() {
    const isProductosSelected = selectProductos.value !== '';

    inputsAfectados.forEach(input => {
        input.disabled = !
            isProductosSelected; // Desactivar si no hay productos seleccionados, activar si hay productos seleccionados
    });

    mensaje.style.display = isProductosSelected ? 'none' : 'block';
    enviar.style.opacity = isProductosSelected ? '1' : '0';
}
toggleInputs();
selectProductos.addEventListener('change', toggleInputs);





const expresiones = {
    stockNum_verificar: /^\d{1,12}$/,
};

function validarFormulario(event) {
    const stockNum = document.querySelector('input[name="stock_actual"]').value.trim();
    const areaValue = document.querySelector('select[name="area"]').value.trim();

    // Verificar si algún campo está vacío
    if (stockNum === '' || areaValue === '') {
        Swal.fire({
            title: 'Campo vacío!',
            text: 'Los campos son obligatorios. Por favor, completa todos los campos.',
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



</html>