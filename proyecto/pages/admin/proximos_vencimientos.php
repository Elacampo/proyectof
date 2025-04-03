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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="../../css/admin.css/admin_user.css?v=<?php echo filemtime('../../css/admin.css/admin_user.css'); ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo filemtime('../../css/dashboard.css'); ?>">
    <link rel="stylesheet"
        href="../../css/admin.css/infoCategoria.css?v=<?php echo filemtime('../../css/admin.css/infoCategoria.css'); ?>">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Productos proximos a vencer</title>
</head>

<body>

    <?php include('includes/nav-bar.php')?>



    <section class="home">

        <div class="inicio">

            <div class="text">
                <div class="info">
                    <h5>Informe productos</h5>
                    <h6>Próximos a Vencer en 30 días o menos</h6>
                    <h6>Ya expirados</h6>
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
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Fecha de Ingreso</th>
                            <th>Fecha de vencimiento</th>
                            <th>Cod. bodega</th>
                            <th>Area</th>
                            <th>Acciónes</th>
                        </tr>
                    </thead>

                    <tbody id="content">

                    </tbody>
                </table>

            </div>

        </div>

    </section>
    <script>
    let paginaActual = 1;
    getData(paginaActual);


    document.getElementById("campo").addEventListener("input", function() {
        let btnReset = document.querySelector('.btnReset');
        if (this.value.trim() !== '') {
            btnReset.classList.add('btnReset-show');
        } else {
            btnReset.classList.remove('btnReset-show');
        }
    });

    document.querySelector('.contenedor-busqueda [type="reset"]').addEventListener('click', function() {
        document.getElementById('campo').value = ''; // Limpiar el valor del input
        document.getElementById('campo').focus(); // Enfocar el input después de limpiar
        document.querySelector('.btnReset').classList.remove('btnReset-show'); // Ocultar el icono de limpieza
        paginaActual = 1; // Resetear a la primera página
        getData(paginaActual); // Obtener datos de la primera página después de limpiar
    });

    document.getElementById("campo").addEventListener("keyup", () => {
        paginaActual = 1; // Resetear a la primera página al buscar
        getData(paginaActual);
    });

    document.getElementById("num_registros").addEventListener("change", () => {
        paginaActual = 1; // Resetear a la primera página al cambiar el número de registros
        getData(paginaActual);
    });

    function getData(pagina) {
        let input = document.getElementById("campo").value;
        let num_registros = document.getElementById("num_registros").value;

        let content = document.getElementById("content");
        let url = "../../php/loadProximoExpirar.php";
        let formData = new FormData();
        formData.append('campo', input);
        formData.append('registros', num_registros);
        formData.append('pagina', pagina);

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {
                content.innerHTML = data.html;
                document.getElementById("currentPage").textContent = pagina; // Update current page number

                // Update total number of pages
                const totalPages = data.totalPages;
                document.getElementById("totalPages").textContent = totalPages;

                // Show or hide the next button based on the number of pages
                const btnNext = document.getElementById("btnNext");
                btnNext.style.display = pagina < totalPages ? "block" : "none";

                // Show or hide the previous button based on the current page
                const btnPrev = document.getElementById("btnPrev");
                btnPrev.style.display = pagina > 1 ? "block" : "none";
            }).catch(err => console.log(err));
    }

    function goToPage(page) {
        paginaActual = page;
        getData(paginaActual);
    }

    function nextPage() {
        paginaActual++;
        document.getElementById("currentPage").textContent = paginaActual; // Actualizar número de página actual
        getData(paginaActual);

        // Deshabilitar el botón "Siguiente" si estamos en la última página
        if (paginaActual >= parseInt(document.getElementById("totalPages").textContent)) {
            document.getElementById("btnNext").style.display = "none"; // Cambiar la opacidad del botón "Siguiente" a 0
        }
    }

    function prevPage() {
        if (paginaActual > 1) {
            paginaActual--;
            document.getElementById("currentPage").textContent = paginaActual; // Actualizar número de página actual
            getData(paginaActual);
            document.getElementById("btnNext").style.display = "block";

        }
    }
    document.getElementById("num_registros").addEventListener("change", () => {
        paginaActual = 1; // Resetear a la primera página al cambiar el número de registros
        getData(paginaActual);
    });

    document.querySelector('.contenedor-busqueda [type="reset"]').addEventListener('click', function() {
        document.getElementById('campo').value = ''; // Limpiar el valor del input
        document.getElementById('campo').focus(); // Enfocar el input después de limpiar
        paginaActual = 1; // Resetear a la primera página
        getData(paginaActual); // Obtener datos de la primera página después de limpiar
    });
    </script>

</body>

<script src="../../js/dashboard.js"></script>

</html>