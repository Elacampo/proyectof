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
    <title>Editar Usuario</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Usuarios</h1>
                    <h6>Editar Usuario</h2>
            </div>

            <div class="informacion-contenedor">
                <?php
                    include('../../php/editar_usuario_controlador.php');
                ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="formulario-pro"
                    onsubmit="validarFormulario(event)">

                    <div class="row-name">
                        <div class="nombre">
                            <label for="">Nombre</label>
                            <input type="text" name="nombre" value="<?php echo $nombre;?>">
                        </div>

                        <div class="apellido">
                            <label for="">Apellido</label>
                            <input type="text" name="apellido" value="<?php echo $apellido;?>">
                        </div>
                    </div>

                    <div class="email">
                        <label for="">Correo</label>
                        <input type="text" name="correo" value="<?php echo $correo;?>">
                    </div>


                    <div class="password">
                        <label for="">Contraseña</label>
                        <input type="password" name="password" id="">
                    </div>

                    <div class="rol">
                        <label for="">Rol</label>
                        <select name="rol">
                            <option value="1" <?php if($rol == 1) echo "selected"; ?>>Administrador</option>
                            <option value="2" <?php if($rol == 2) echo "selected"; ?>>Farmacéutico</option>
                            <option value="3" <?php if($rol == 3) echo "selected"; ?>>Cliente</option>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id;?>">

                    <div class="buttons">
                        <input type="submit" value="Actualizar" name="btnEnviar">

                        <a href="usuarios.php" class="btn-volver">Volver</a>
                        <a href="../../php/eliminar_usuario.php?id=<?php echo $id; ?>" onclick="alertaEliminar(event)"
                            class="borrar-icon" title="Eliminar"><i class="bx bxs-trash bx-md"></i></a>
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
    nombreUsuario: /^[a-zA-ZÀ-ÿ\s]{1,255}$/, // Letras y espacios, pueden llevar acentos.
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
};

function validarFormulario(event) {
    var nombre = document.querySelector('input[name="nombre"]').value.trim();
    var apellido = document.querySelector('input[name="apellido"]').value.trim();
    var correo = document.querySelector('input[name="correo"]').value.trim();

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

// Función para mostrar una alerta de confirmación antes de eliminar un usuario
function alertaEliminar(event) {
    event.preventDefault();
    let href = event.currentTarget.getAttribute('href');
    let userId = href.split('=')[1];

    Swal.fire({
        title: "¿Estás seguro que deseas eliminar este usuario?",
        text: "Una vez eliminado, no podrás recuperarlo",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#45b867",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar usuario."
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Usuario Eliminado!",
                text: "El usuario ha sido eliminado del sistema.",
                icon: "success",
                showConfirmButton: true,
                confirmButtonColor: "#45b867",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = href;
            });

        }
    });
}
</script>


</html>