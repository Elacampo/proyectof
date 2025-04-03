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
    <title>Agregar Categoria</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>



    <section class="home">

        <div class="inicio">

            <div class="text">
                <h5>Categoria</h1>
                    <h6>Agregar Categoria</h2>

            </div>

            <div class="information-container">

                <?php 
                        include('../../php/agregar_categoria_controlador.php');
                    ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="provider-form"
                    onsubmit="validarFormulario(event)">

                    <div class="name">
                        <label for="">Nombre</label>
                        <input type="text" name="nombre" autocomplete="off" value="">
                    </div>

                    <div class="buttons">
                        <a href="categoria.php" class="btn-volver">Volver</a>
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
    nombreCategoria: /^[a-zA-ZÀ-ÿ\s]{1,255}$/ // Letras y espacios, máximo 255 caracteres.
};


function validarFormulario(event) {
    var nombre = document.querySelector('input[name="nombre"]').value.trim();

    // Verificar si algún campo está vacío
    if (nombre.trim() === '') {
        Swal.fire({
            title: 'Campo vacío!',
            text: 'El nombre de la categoría es obligatorio.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Verificar si en el nombre se ha ingresado un número
    if (!expresiones.nombreCategoria.test(nombre)) {
        Swal.fire({
            title: 'Error!',
            text: 'El nombre de la categoría solo puede contener letras y espacios.',
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