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
include ('../../php/conecta.php');

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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="../../css/admin.css/infoProducto.css">
    <link rel="stylesheet" href="../../css/admin.css/admin_user.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Agregar producto</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>


    <section class="home">

        <div class="inicio">

            <div class="text">

                <div class="info">
                    <h5>Producto</h1>
                        <h6>Agregar Producto</h2>
                </div>

                <div class="volver">
                    <a href="productos.php" id="btnVolver">Volver</a>
                </div>

            </div>

            <div class="information-container">

                <?php include('../../php/agregar_producto_controlador.php'); ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" onsubmit="validarFormulario(event)"
                    enctype="multipart/form-data">

                    <div class="info-izquierda">

                        <div class="info_card">

                            <imagen class="imagen_info">
                                <label for="">Nombre producto</label>
                                <input type="text" name="nombre" style="width:50%">

                                <figure>
                                    <label for="texto_imagen" style="font-size:12px">Ingresa la imagen del
                                        producto</label>
                                    <img src="../../img/productos/no-imagen.jpg" alt="">
                                </figure>
                                <input type="file" name="imagen" class="custom-file-input">

                            </imagen>

                            <div class="descripcion">
                                <h2>Descripción <h5>(Utiliza un guion seguido de un espacio antes de cada palabra para
                                        los subtítulos.)</h5>
                                </h2>
                                <textarea name="descripcion" placeholder="Ingresa la descripción aqui..."></textarea>

                                <p style="overflow:hidden; opacity:0;height:0;padding:0; ">Lorem, ipsum dolor sit amet
                                    consectetur adipisicing elit. Facere, eligendi ea sed perferendis inventore ullam
                                    laborum exercitationem at minus numquam minima quas, nam assumenda tenetur velit
                                    voluptate? Similique, reiciendis temporibus!</p>
                            </div>
                        </div>


                    </div>

                    <div class="info-derecha">

                        <div class="fila filaEditar">

                            <div class="categoria">
                                <label for="categoria">Categoria</label>


                                <select name="categoria">

                                    <?php foreach ($resultCategorias as $opc_categoria): ?>
                                    <option value="<?php echo $opc_categoria['id_cat'] ?>">
                                        <?php echo $opc_categoria['nombre'] ?>
                                    </option>
                                    <?php endforeach ?>

                                </select>
                            </div>

                            <div class="unidad_medida">
                                <label for="Medida">Unidad de medida</label>
                                <input type="text" name="unidad_medida" value="">
                            </div>


                            <div class="precio">
                                <label for="precio">Precio</label>
                                <input type="text" name='precio'>


                            </div>
                        </div>

                        <div class="fila filaEditar">

                            <div class="fecha_ingreso">
                                <label for="fecha_ingreso">Fecha Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ing">
                            </div>

                            <div class="fecha_ven">
                                <label for="fecha_ven">Fecha vencimiento</label>
                                <div class="alerta_ven" style="display: flex;">
                                    <input type="date" name="fecha_vencimiento" id="fecha_ven">

                                    <div class="vencimiento" style="display: none;">
                                        <i class='bx bx-error-circle bx-sm'></i>
                                        <h5>Expirado</h5>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="fila filaEditar">

                            <div class="proveedor">
                                <label for="proveedor">Proveedor</label>

                                <select name="proveedor" style="width: 50%;">
                                    <?php foreach ($resultProveedor as $opc_proveedor): ?>

                                    <option value="<?php echo $opc_proveedor['proveedor_id'] ?>">
                                        <?php echo $opc_proveedor['nombre'] ?>
                                    </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="qr_code">
                            <label for="qr_code">Código QR: Generar después de crear el producto</label>
                            <img src="../../img/productos/no-qr.png" alt="" style="width:30% ">
                        </div>
                        <input type="submit" value="Agregar" name="btnEnviar">
                    </div>

                </form>


                <p></p>
            </div>
        </div>
        </div>
    </section>

</body>


<script>
const expresiones = {
    producto_verificar: /^[a-zA-Z0-9\s]{1,100}$/,
    unidad_medida_verificar: /^[a-zA-Z\s.,-]{1,20}$/,
};

function validarFormulario(event) {
    const nombreProducto = document.querySelector('input[name="nombre"]').value.trim();
    const descripcionProducto = document.querySelector('textarea[name="descripcion"]').value.trim();
    const unidad_medida = document.querySelector('input[name="unidad_medida"]').value.trim();
    const fechaIngreso = document.getElementById('fecha_ing').value.trim();
    const fechaVencimiento = document.getElementById('fecha_ven').value.trim();
    const precio = document.querySelector('input[name="precio"]').value.trim();



    if (nombreProducto === '' || unidad_medida === '' || descripcionProducto === '' || fechaIngreso === '' ||
        fechaVencimiento === '' || precio === '') {
        mostrarError('Todos los campos son obligatorios. Por favor, completa todos los campos.');
        event.preventDefault();
        return false;
    }

    if (isNaN(parseFloat(precio))) {
        mostrarError('El valor del producto debe ser un número válido.');
        event.preventDefault();
        return false;
    }

    if (precio < 0) {
        mostrarError('El precio debe ser un numero positivo.');
        event.preventDefault();
        return false;
    }

    if (!expresiones.precio.test(precio)) {
        mostrarError(
            'El valor del producto debe estar en el formato correcto, por ejemplo: 1234.56 o 1,234. Utiliza coma como separador de decimales y punto como separador de miles.'
            )
        event.preventDefault();
        return false;
    }

    if (!expresiones.producto_verificar.test(descripcionProducto)) {
        mostrarError(
            'La longitud de la descripción es de máximo 100 caracteres y no debe contener caracteres especiales.');
        event.preventDefault();
        return false;
    }

    if (!expresiones.producto_verificar.test(nombreProducto)) {
        mostrarError('La longitud del nombre es de máximo 100 caracteres y no debe contener caracteres especiales.');
        event.preventDefault();
        return false;
    }

    if (!expresiones.unidad_medida_verificar.test(unidad_medida)) {
        mostrarError('La unidad de medida debe tener máximo 20 caracteres y no contener números.');
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

const areaSelect = document.getElementById('areaSelect');
const codBodegaInput = document.getElementById('cod_bodega');

areaSelect.addEventListener('change', function() {
    const selectedValue = areaSelect.value;
    codBodegaInput.value = selectedValue;
});


document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos relevantes
    const fechaVencimientoInput = document.querySelector('.fecha_ven input[type="date"]');
    const vencimientoDiv = document.querySelector('.fecha_ven .vencimiento');

    // Función para verificar si la fecha está expirada
    function verificarExpiracion() {
        const fechaVencimiento = new Date(fechaVencimientoInput.value);
        const hoy = new Date();

        if (fechaVencimiento < hoy) {
            vencimientoDiv.style.display = 'flex'; // Mostrar mensaje de expirado
        } else {
            vencimientoDiv.style.display = 'none'; // Ocultar mensaje de expirado
        }
    }

    // Verificar la expiración al cargar la página
    verificarExpiracion();

    // Verificar la expiración al cambiar la fecha de vencimiento
    fechaVencimientoInput.addEventListener('change', verificarExpiracion);
});
</script>

<script src="../../js/dashboard.js"></script>



</html>