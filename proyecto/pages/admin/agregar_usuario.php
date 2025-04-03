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


    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Agregar Usuario</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>



    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Usuarios</h1>
                    <h6>Añadir Usuario</h2>
            </div>

            <div class="informacion-contenedor">
                <?php
                    include('../../php/agregar_usuario_controlador.php');
                ?>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" onsubmit="validarFormulario(event)">

                    <div class="row-name">
                        <div class="nombre">
                            <label for="">Nombre</label>
                            <input type="text" name="nombre" class="nombre">
                        </div>

                        <div class="apellido">
                            <label for="">Apellido</label>
                            <input type="text" name="apellido" class="apellido">
                        </div>
                    </div>

                    <div class="email">
                        <label for="">Correo</label>
                        <input type="text" name="correo" class="c">
                    </div>


                    <div class="password">
                        <label for="">Contraseña</label>
                        <input type="password" name="password" class="">
                    </div>

                    <div class="rol">
                        <label for="">Rol</label>
                        <select name="rol">
                            <option value="1">Administrador</option>
                            <option value="2">Farmacéutico</option>
                            <option value="3">Cliente</option>
                        </select>
                    </div>

                    <div class="buttons">
                        <a href="usuarios.php" class="btn-volver">Volver</a>
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
    usuario: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, números, guion y guion_bajo
    nombre: /^[a-zA-ZÀ-ÿ\s]{1,20}$/, // Letras y espacios, pueden llevar acentos.
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, // Mínimo 8 caracteres, al menos una letra mayúscula, una letra minúscula, un número y un carácter especial
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
};

function validarFormulario(event) {
    var nombre = document.querySelector('input[name="nombre"]').value;
    var apellido = document.querySelector('input[name="apellido"]').value;
    var correo = document.querySelector('input[name="correo"]').value;
    var password = document.querySelector('input[name="password"]').value;
    var rol = document.querySelector('select[name="rol"]').value;

    // Verificar si algún campo está vacío
    if (nombre === '' || apellido === '' || correo === '' || password === '' || rol === '') {
        Swal.fire({
            title: 'Campos vacíos!',
            text: 'Todos los campos son obligatorios. Por favor, completa todos los campos.',
            icon: 'error'
        });
        event.preventDefault();
        return;
    }

    // Verificar si en el nombre o apellido se ha ingresado un número
    if (!expresiones.nombre.test(nombre) || !expresiones.nombre.test(apellido)) {
        Swal.fire({
            title: 'Error!',
            text: 'El nombre o el apellido no pueden contener números.',
            icon: 'error'
        });
        event.preventDefault();
        return;
    }

    // Verificar el formato del correo electrónico
    if (!expresiones.correo.test(correo)) {
        Swal.fire({
            title: 'Error!',
            text: 'Por favor, ingresa un correo electrónico válido.',
            icon: 'error'
        });
        event.preventDefault();
        return;
    }
}
</script>



</html>