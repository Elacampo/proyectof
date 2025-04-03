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
    <title>Editar producto</title>
</head>

<body>

    <?php include ('includes/nav-bar.php')?>
    <section class="home">

        <div class="inicio">

            <div class="text">

                <div class="info">
                    <h5>Producto</h1>
                        <h6>Editar Producto</h2>
                </div>

                <div class="volver">
                    <a href="productos.php" id="btnVolver">Volver</a>
                </div>

            </div>

            <div class="information-container">

                <?php include('../../php/editar_producto_controlador.php'); ?>

                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" onsubmit="validarFormulario(event)"
                    enctype="multipart/form-data">

                    <div class="info-izquierda">

                        <div class="info_card">

                            <imagen class="imagen_info">
                                <label for="">Nombre producto</label>
                                <input type="text" name="nombre" style="width: 50%" value="<?php echo $nombre; ?>">

                                <figure>

                                    <label for="texto_imagen" style="font-size:12px">Imagen del producto</label>
                                    <img src="../../img/productos/<?php echo $imagen?>" alt="">
                                </figure>

                                <div class="btn_img">
                                    <input type="file" name="imagen" class="custom-file-input">
                                    <?php if($imagen != 'no-imagen.jpg'){ ?>
                                    <a href="../../php/eliminar_imagen.php?id=<?=$id?>"
                                        onclick="alertaEliminar_imagen(event)">
                                        <i class='bx bxs-camera-off'></i>
                                        Eliminar imagen</a>
                                    <?php } ?>
                                </div>




                            </imagen>

                            <div class="descripcion">
                                <h2>Descripción<h5>(Utiliza un guion seguido de un espacio antes de cada palabra para
                                        los subtítulos.)</h5>
                                </h2>

                                <textarea name="descripcion" id=""
                                    placeholder="Ingresa la descripción aqui..."><?php echo $descripcion;?></textarea>
                                <p style="overflow:hidden; opacity:0;height:0;padding:0; ">Lorem, ipsum dolor sit
                                    amet
                                    consectetur adipisicing elit. Facere, eligendi ea sed perferendis inventore
                                    ullam
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
                                    <option value="<?php echo $opc_categoria['id_cat'] ?>"
                                        <?php echo ($opc_categoria['id_cat'] == $row['categoria']) ? 'selected' : ''; ?>>
                                        <?php echo $opc_categoria['nombre'] ?>
                                    </option>
                                    <?php endforeach ?>

                                </select>
                            </div>

                            <div class="unidad_medida">
                                <label for="Medida">Unidad de medida</label>
                                <input type="text" name="unidad_medida" value="<?php echo $unidad_medida; ?>">
                            </div>


                            <div class="precio">
                                <label for="precio">Precio</label>
                                <input type="text" name='precio_producto' value="<?php echo $row['precio_producto']?>">
                            </div>
                        </div>

                        <div class="fila filaEditar">

                            <div class="cod_bodega">
                                <label for="cod_bodega">Cod. Bodega</label>
                                <?php if ($activarH4): ?>
                                <input type="text" readonly value=''>
                                <?php else :?>
                                <input type="text" name="cod_bodega" id="cod_bodega"
                                    value="<?php echo $row['ubicacion'];?>" readonly>
                                <?php endif; ?>

                            </div>

                            <div class="area">

                                <label for="area">Area</label>
                                <input type="hidden" name="area" value="<?php echo $row['ubicacion']; ?>">

                                <?php if ($activarH4): ?>
                                <input type="text" name="area" value='' readonly>
                                <?php else :?>
                                <select name="area" id="areaSelect">
                                    <option value="" disabled selected>Seleccione Aqui</option>
                                    <?php foreach ($result_espacio as $opc_espacio): ?>
                                    <option value="<?php echo $opc_espacio['id_bodega'] ?>">
                                        <?php echo $opc_espacio['area'] ?>
                                    </option>
                                    <?php endforeach ?>
                                </select>

                                <?php endif; ?>

                            </div>

                            <div class="stock_actual">
                                <label for="stock_actual">Stock actual</label>

                                <?php if ($activarH4): ?>
                                <a href="agregar_stock.php" class="aviso">
                                    <h4 class="noStockAsignado" style="gap:5px;align-items:center">
                                        <i class='bx bxs-edit-alt'></i>
                                        Asigna Aqui
                                    </h4>
                                </a>
                                <?php else :?>
                                <input type="text" name="stock" value="<?php echo $stock_actual;?>">
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="fila filaEditar">

                            <div class="fecha_ingreso">
                                <label for="fecha_ingreso">Fecha Ingreso</label>
                                <input type="date" name="fecha_ingreso" value="<?php echo $row['fecha_ingreso']; ?>">
                            </div>

                            <div class="fecha_ven">
                                <label for="fecha_ven">Fecha vencimiento</label>
                                <div class="alerta_ven" style="display: flex;">
                                    <input type="date" name="fecha_vencimiento"
                                        value="<?php echo $fecha_vencimiento; ?>">

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

                                    <option value="<?php echo $opc_proveedor['proveedor_id'] ?>"
                                        <?php echo ($opc_proveedor['proveedor_id'] == $row['proveedor']) ? 'selected' : ''; ?>>
                                        <?php echo $opc_proveedor['nombre'] ?>
                                    </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>


                        <div class="qr_code">
                            <label for="qr_code">Codigo QR</label>
                            <figure class="codigo_qr">
                                <?php if (!empty($row['qr_imagen'])): ?>
                                <img src="../../img/codigos_qr/<?php echo $row['qr_imagen']?>" alt="Código QR generado">

                                <div class="btn_img">
                                    <a href="../../php/eliminar_qr.php?id=<?=$id?>"
                                        onclick="alertaEliminar_imagen(event)">
                                        <i class='bx bx-qr'></i>
                                        Eliminar QR</a>

                                    <?php else: ?>
                                    <img src="../../img/codigos_qr/no-qr.png" alt="No hay código QR generado"
                                        style="width:30%">
                                    <?php endif; ?>
                                    <?php if (empty($row['qr_imagen'])): ?>
                                    <input type="submit" value="Generar QR" name="btnGenerar" class="generar_qr">
                                    <?php endif; ?>
                            </figure>

                        </div>
                        <div class="botones">
                            <input type="submit" value="Actualizar" name="btnEnviar" style="height:100%">

                            <a href="../../php/eliminar_producto.php?id=<?php echo $id; ?>"
                                onclick="alertaEliminar(event)" class="borrar-icon" title="Eliminar"><i
                                    class="bx bxs-trash bx-md"></i></a>

                        </div>
                    </div>

                    <input type="hidden" name="producto_id" value="<?php echo $id;?>">


                </form>


                <p></p>
            </div>
        </div>
        </div>
    </section>

</body>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el input de cod_bodega
    const codBodegaInput = document.getElementById('cod_bodega');

    // Establecer su valor como una cadena vacía
    codBodegaInput.value = '';
});

const areaSelect = document.getElementById('areaSelect');
const codBodegaInput = document.getElementById('cod_bodega');

areaSelect.addEventListener('change', function() {
    const selectedValue = areaSelect.value;
    codBodegaInput.value = selectedValue;
});

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
    producto_verificar: /^[a-zA-Z0-9\s]{1,100}$/,
    unidad_medida_verificar: /^[a-zA-Z\s.,-]{1,20}$/,
    stockNum_verificar: /^\d{1,12}$/,
};

function validarFormulario(event) {
    const nombreProducto = document.querySelector('input[name="nombre"]').value.trim();
    const unidad_medida = document.querySelector('input[name="unidad_medida"]').value.trim();
    const stockNum = document.querySelector('input[name="stock"]').value.trim();
    const area = document.querySelector('select[name="area"]').value.trim();
    const precio = document.querySelector('input[name="precio_producto"]').value.trim();

    if (isNaN(parseFloat(precio))) {
        mostrarError('El valor del producto debe ser un número válido.');
        event.preventDefault();
        return false;
    }

    if (isNaN(parseInt(stockNum))) {
        mostrarError('El valor del stock debe ser un número válido.');
        event.preventDefault();
        return false;
    }

    if (stockNum < 0) {
        mostrarError('El stock debe ser un número entero positivo.');
        event.preventDefault();
        return false;
    }

    if (precio < 0) {
        mostrarError('El precio debe ser un numero positivo.');
        event.preventDefault();
        return false;
    }

    if (nombreProducto === '' || unidad_medida === '' || stockNum === '' || precio === '') {
        mostrarError('Todos los campos son obligatorios. Por favor, completa todos los campos.');
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

    if (!expresiones.producto_verificar.test(nombreProducto)) {
        mostrarError('La longitud del nombre es de maximo 100 caracteres y no debe contener caracteres especiales.');
        event.preventDefault();
        return false;
    }

    if (!expresiones.unidad_medida_verificar.test(unidad_medida)) {
        mostrarError('La unidad de medida debe tener máximo 20 caracteres y no contener números.');
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

function alertaEliminar_imagen(event) {
    event.preventDefault();
    let href = event.currentTarget.getAttribute('href');
    let userId = href.split('=')[1];

    Swal.fire({
        title: "¿Estás seguro que deseas eliminar esta imagen?",
        text: "Una vez eliminado, no podrás recuperarlo",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#45b867",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar imagen."
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Imagen Eliminada!",
                text: "La imagen ha sido eliminado del sistema.",
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

function alertaEliminar(event) {
    event.preventDefault();
    let href = event.currentTarget.getAttribute('href');
    let userId = href.split('=')[1];

    Swal.fire({
        title: "¿Estás seguro que deseas eliminar este producto?",
        text: "Una vez eliminado, no podrás recuperarlo",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#45b867",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar producto."
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Producto Eliminado!",
                text: "El producto ha sido eliminado del sistema.",
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