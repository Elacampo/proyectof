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
    <title>Agregar Proveedor</title>
</head>

<body>

<?php include('includes/nav-bar.php')?>



    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Proveedores</h1>
                    <h6>Agregar Proveedor</h2>

            </div>

            <div class="information-container">

                <?php 
                    include('../../php/agregar_proveedor_controlador.php');
                ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="provider-form"
                    onsubmit="validarFormulario(event)">

                    <div class="name">
                        <label for="">Nombre</label>
                        <input type="text" name="nombre" autocomplete="off">
                    </div>

                    <div class="phone">
                        <label for="">Celular</label>
                        <input type="text" name="celular" autocomplete="off">
                    </div>

                    <div class="email">
                        <label for="">Correo</label>
                        <input type="text" name="correo" autocomplete="off">
                    </div>

                    <input type="hidden" name="proveedor_id">

                    <div class="buttons">
                        <a href="proveedores.php" class="btn-volver">Volver</a>
                        <input type="submit" value="Agregar" name="btnEnviar">
                    </div>
                </form>
            </div>

        </div>
        </div>

    </section>

</body>

<script src="../../js/dashboard.js"></script>
<script>
const expresiones = {
    nombreProveedor: /^[a-zA-ZÀ-ÿ\s]{1,255}$/, // Letras y espacios, pueden llevar acentos.
    celular: /^\d{10,15}$/, // Número de celular de Colombia, de 10 a 15 dígitos.
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
};

function validarFormulario(event) {
    var nombre = document.querySelector('input[name="nombre"]').value;
    var celular = document.querySelector('input[name="celular"]').value;
    var correo = document.querySelector('input[name="correo"]').value;

    // Verificar si algún campo está vacío
    if (nombre.trim() === '' || celular.trim() === '' || correo.trim() === '') {
        Swal.fire({
            title: 'Campos vacíos!',
            text: 'Todos los campos son obligatorios. Por favor, completa todos los campos.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Verificar si en el nombre se ha ingresado un número
    if (!expresiones.nombreProveedor.test(nombre)) {
        Swal.fire({
            title: 'Error!',
            text: 'El nombre no pueden contener números y debe ser válido.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Validar el número de celular
    if (!expresiones.celular.test(celular)) {
        Swal.fire({
            title: 'Error!',
            text: 'El número de celular no es válido.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Verificar el formato del correo electrónico
    if (!expresiones.correo.test(correo)) {
        Swal.fire({
            title: 'Error!',
            text: 'Por favor, ingresa un correo electrónico válido.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    return true;
}
</script>


</script>

</html>