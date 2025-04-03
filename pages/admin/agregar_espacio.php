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
        <title>Editar Espacio</title>
    </head>

    <body>

        <?php include('includes/nav-bar.php')?>


        <section class="home">

            <div class="inicio">

                <div class="text">
                    <h5>Espacio</h1>
                        <h6>Añadir Espacio</h2>
                </div>

                <div class="information-container">

                    <?php 
        include('../../php/agregar_espacio_controlador.php'); 
        ?>

                    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="provider-form"
                        onsubmit="validarFormulario(event)">

                        <div class="row-name">
                            <div class="nombre">
                                <label for="">Nombre de bodega</label>
                                <input type="text" name="id_nuevo_bodega" value="">
                            </div>

                            <div class="apellido">
                                <label for="">Capacidad Maxima</label>
                                <input type="text" name="cap_max" value="">
                            </div>
                        </div>

                        <div class="email">
                            <label for="">Area</label>
                            <input type="text" name="area" value="">


                            <div class="rol">
                                <label for="">Estado</label>
                                <select name="estado">
                                    <option value="1">Disponible</option>
                                    <option value="2">Ocupado</option>
                                    <option value="3">En mantenimiento</option>
                                    <option value="4">Completo</option>
                                    <option value="5">Inactivo</option>
                                </select>
                            </div>

                            <input type="hidden" name="id_bodega">

                            <div class="buttons">
                                <input type="submit" value="Agregar" name="btnEnviar">
                                <a href="espacio.php" class="btn-volver">Volver</a>
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
    nombreArea: /^[a-zA-ZÀ-ÿ\s,.]*[^\s.,?!][a-zA-ZÀ-ÿ\s,.]*$/,
    capacidadMaxima: /^\d{1,3}$/,
    nombre_bodega: /^[a-zA-Z]\d{3}$/
};

function validarFormulario(event) {
    const nombreArea = document.querySelector('input[name="area"]').value.trim();
    const capacidadMaxima = document.querySelector('input[name="cap_max"]').value.trim();
    const nombre_bodega = document.querySelector('input[name="id_nuevo_bodega"]').value.trim();

    // Verificar si algún campo está vacío
    if (nombreArea === '' || capacidadMaxima === '' || nombre_bodega === '') {
        Swal.fire({
            title: 'Campos vacíos!',
            text: 'Todos los campos son obligatorios.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Verificar si en el nombre se ha ingresado un número
    if (!expresiones.nombreArea.test(nombreArea)) {
        Swal.fire({
            title: 'Error!',
            text: 'Error: El nombre del area debe tener un formato válido.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    // Verificar si en el nombre se ha ingresado un número
    if (!expresiones.nombre_bodega.test(nombre_bodega)) {
        Swal.fire({
            title: 'Error!',
            text: 'Error: El nombre de la bodega debe tener un formato válido, por ejemplo, A001, B003, etc.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }


    // Verificar si la capacidad máxima es un número válido
    if (!expresiones.capacidadMaxima.test(capacidadMaxima)) {
        Swal.fire({
            title: 'Error!',
            text: 'La capacidad máxima debe ser un número válido.',
            icon: 'error'
        });
        event.preventDefault();
        return false;
    }

    return true;
}
    </script>


    </html>