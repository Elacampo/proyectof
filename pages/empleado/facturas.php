
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="../../css/admin.css/admin_user.css?v=<?php echo filemtime('../../css/admin.css/admin_user.css'); ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo filemtime('../../css/dashboard.css'); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Administrar Facturas</title>
</head>


<body>

    <?php include('includes/nav-bar.php')?>

    <section class="home">

        <div class="inicio">

            <div class="text">

                <div class="info">
                    <h5>Facturas</h1>
                        <h6>Consultar facturas</h2>
                </div>

            </div>


            <div class="contenedor-busqueda">

                <form action="" class="form-search">
                    <label for="">Buscar: </label>
                    <div class="input-container">
                        <input type="search" id="campo" name="campo" class="busqueda" autocomplete="off"
                            placeholder="Ingresa tu busqueda aqui...">
                        <button type="reset" title="Limpiar la busqueda" class="btnReset"><i
                                class='bx bx-x-circle bx-sm icono-busqueda'></i></button>
                    </div>
                </form>

                <div class="pagination-buttons">
                    <button id="btnPrev" onclick="prevPage()">Anterior</button>
                    <span>Página <span id="currentPage">1</span> de <span id="totalPages">N</span></span>
                    <button id="btnNext" onclick="nextPage()">Siguiente</button>
                </div>

                <div class="custom-select">
                    <label for="num_registros">Mostrar:</label>
                    <select name="num_registros" id="num_registros" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <label for="num_registros">Registros</label>
                </div>


            </div>

            <div class="contenedor-resultados">

                <table class="tabla-resultados">
                    <thead>
                        <tr>
                            <th>ID Factura</th>
                            <th>Cliente</th>
                            <th>Fecha Emision</th>
                            <th>Fecha limite</th>
                            <th>Estado</th>
                            <th>Acciónes</th>
                        </tr>
                    </thead>
                    <tbody id="content">
                    </tbody>
                </table>

            </div>


    </section>


    <div id="popupModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" style="float: right; cursor: pointer;">&times;</span>
            <div id="contenidoEstado">
                <!-- Aquí se cargará el contenido de pagina2.html -->
            </div>
        </div>
    </div>

    <?php
if(isset($_GET['success'])) {
    if ($_GET['success'] === 'true' && isset($_GET['message'])) {
        $success_message = $_GET['message'];
        echo "<script>
        Swal.fire({
            title: 'Éxito',
            text: '$success_message',
            icon: 'success'
        });
        </script>";
    } elseif ($_GET['success'] === 'false' && isset($_GET['message'])) {
        $error_message = $_GET['message'];
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: '$error_message',
            icon: 'error'
        });
        </script>";
    }
}
?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si se mostró el Sweet Alert y eliminar los parámetros del URL
        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');
        if (successParam === 'true') {
            // Eliminar los parámetros del URL y redireccionar a la misma página
            const urlWithoutParams = window.location.pathname;
            history.replaceState({}, document.title, urlWithoutParams);
        }
    });
    </script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelector('.contenedor-resultados').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('openModalBtn') || event.target.closest(
                    '.openModalBtn')) {
                var modal = document.getElementById('popupModal');
                modal.style.display = 'block';

                var facturaId = event.target.getAttribute('data-id-factura');

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('contenidoEstado').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'editar_factura.php?id_factura=' + facturaId, true);
                xhr.send();
            }
            document.getElementById('closeModal').addEventListener('click', function() {
                var modal = document.getElementById('popupModal');
                modal.style.display = 'none';

                document.getElementById('btnActualizar').addEventListener('click', function() {
                    var idFactura = document.getElementById('id_factura').value;
                    var nuevoEstado = document.getElementById('estado_factura').value;

                    var formData = new FormData();
                    formData.append('id_factura', idFactura);
                    formData.append('estado_factura', nuevoEstado);

                    fetch('actualizar_factura.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                handleUpdateSuccess(data.message);
                            } else {
                                handleUpdateError(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });

        // Variable para almacenar la página actual
        let paginaActual = 1;

        // Obtener datos al cargar la página
        getData(paginaActual);

        // Evento para gestionar la búsqueda y mostrar el botón de limpiar
        document.getElementById("campo").addEventListener("input", function() {
            let btnReset = document.querySelector('.btnReset');
            if (this.value.trim() !== '') {
                btnReset.classList.add('btnReset-show');
            } else {
                btnReset.classList.remove('btnReset-show');
            }
        });

        // Evento para limpiar la búsqueda y resetear la página
        document.querySelector('.contenedor-busqueda [type="reset"]').addEventListener('click', function() {
            document.getElementById('campo').value = '';
            document.getElementById('campo').focus();
            document.querySelector('.btnReset').classList.remove('btnReset-show');
            paginaActual = 1;
            getData(paginaActual);
        });

        // Evento para buscar
        document.getElementById("campo").addEventListener("keyup", () => {
            paginaActual = 1;
            getData(paginaActual);
        });

        // Evento para cambiar el número de registros por página
        document.getElementById("num_registros").addEventListener("change", () => {
            paginaActual = 1;
            getData(paginaActual);
        });

        // Función para obtener y mostrar los datos
        function getData(pagina) {
            let input = document.getElementById("campo").value;
            let num_registros = document.getElementById("num_registros").value;

            let content = document.getElementById("content");
            let url = "../../php/loadFacturas.php";
            let formData = new FormData();
            formData.append('campo', input);
            formData.append('registros', num_registros);
            formData.append('pagina', pagina);

            fetch(url, {
                    method: "POST",
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    // Mostrar los datos
                    content.innerHTML = data.html;

                    // Actualizar el número de página actual
                    document.getElementById("currentPage").textContent = pagina;

                    // Actualizar el número total de páginas
                    document.getElementById("totalPages").textContent = data.totalPages;

                    // Mostrar u ocultar botones de navegación según la página actual
                    const btnNext = document.getElementById("btnNext");
                    btnNext.style.display = pagina < data.totalPages ? "block" : "none";

                    const btnPrev = document.getElementById("btnPrev");
                    btnPrev.style.display = pagina > 1 ? "block" : "none";
                }).catch(err => console.log(err));
        }

        // Función para ir a una página específica
        function goToPage(page) {
            paginaActual = page;
            getData(paginaActual);
        }

        // Función para ir a la siguiente página
        function nextPage() {
            paginaActual++;
            document.getElementById("currentPage").textContent = paginaActual;
            getData(paginaActual);

            // Deshabilitar el botón "Siguiente" si estamos en la última página
            if (paginaActual >= parseInt(document.getElementById("totalPages").textContent)) {
                document.getElementById("btnNext").style.display = "none";
            }
        }

        // Función para ir a la página anterior
        function prevPage() {
            if (paginaActual > 1) {
                paginaActual--;
                document.getElementById("currentPage").textContent = paginaActual;
                getData(paginaActual);
                document.getElementById("btnNext").style.display = "block";
            }
        }

        // Evento para cambiar el número de registros por página
        document.getElementById("num_registros").addEventListener("change", () => {
            paginaActual = 1;
            getData(paginaActual);
        });

        // Evento para limpiar la búsqueda y resetear la página
        document.querySelector('.contenedor-busqueda [type="reset"]').addEventListener('click', function() {
            document.getElementById('campo').value = '';
            document.getElementById('campo').focus();
            paginaActual = 1;
            getData(paginaActual);
        });
    });
    </script>

</body>
<script src="../../js/dashboard.js"></script>

</html>