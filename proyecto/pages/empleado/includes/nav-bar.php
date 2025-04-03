<nav class="sidebar close">
        <header>
            <div class="image-text">

                <!-- LOGO USUARIO -->
                <span class="image">
                    <img src="../../img/farma.png" alt="">
                </span>
                <!-- LOGO USUARIO -->

                <!-- NOMBRE Y ROL -->
                <div class="text header-text">

                    <span class="name">
                        <?php 
                        echo ucfirst($_SESSION['nombre'])," ", ucfirst($_SESSION['apellido']);
                    ?>
                    </span>
                    <SPAN class="profession">Farmaceutico</SPAN>
                </div>
                <!-- NOMBRE Y ROL -->

                <i class='bx bx-chevron-right toggle'></i>
            </div>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                </li>

                <ul class="menu-links first">
                    <li class="nav-link">
                        <a href="farmaceutico.php">
                            <i class='bx bxs-home icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                </ul>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="clientes.php">
                            <i class='bx bxs-group icon'></i>
                            <span class="text nav-text">Clientes</span>
                        </a>
                    </li>
                </ul>

                <ul class="menu-links">
                    <li class="nav-link ">
                        <a href="#inventario.php" class="list_button--click">
                            <i class='bx bxs-capsule icon'></i>
                            <span class="text nav-text">Inventario</span>
                            <i id="icon-arrow" class='bx bx-chevron-down icon down'></i>
                        </a>

                    </li>
                </ul>

                <ul class="menu-links--inside">
                    <li class="list_inside">
                        <a href="productos.php">
                            <i class='bx bxs-package icon'></i>
                            <span class="text nav-text">Gestión de productos</span>
                        </a>
                    </li>

                    <li class="list_inside">
                        <a href="categoria.php">
                            <i class='bx bx-category icon'></i>
                            <span class="text nav-text">Consultar categoria</span>
                        </a>
                    </li>

                    <li class="list_inside">
                        <a href="espacio.php">
                            <i class='bx bx-cabinet icon'></i>
                            <span class="text nav-text">Consultar espacio</span>
                        </a>
                    </li>

                    <li class="list_inside">
                        <a href="stock.php">
                            <i class='bx bx-box icon'></i>
                            <span class="text nav-text">Gestión stock</span>
                        </a>
                    </li>

                </ul>


                <ul class="menu-links">
                    <li class="nav-link ">
                        <a href="#reporte.php" class="list_button--click">
                            <i class='bx bxs-report icon'></i>
                            <span class="text nav-text">Reportes</span>
                            <i id="icon-arrow-report" class='bx bx-chevron-down icon down'></i>
                        </a>
                    </li>
                </ul>

                <ul class="menu-links--inside--report">
                    <li class="list_inside">
                        <a href="proximos_vencimientos.php">
                            <i class='bx bxs-calendar-exclamation icon'></i>
                            <span class="text nav-text">Proximo a Expirar / Expirados</span>
                        </a>
                    </li>

                    <li class="list_inside">
                        <a href="stock-info.php">
                            <i class='bx bx-x-circle icon' style='background-color:transparent'></i>
                            <span class="text nav-text">Fuera de Stock</span>
                        </a>
                    </li>
                </ul>

                <ul class="menu-links">
                <li class="nav-link ">
                    <a href="facturas.php" class="list_button--click">
                        <i class='bx bxs-receipt icon'></i>
                        <span class="text nav-text">Facturas</span>
                    </a>
                </li>
            </ul>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="proveedores.php">
                            <i class='bx bxs-truck icon'></i>
                            <span class="text nav-text">Proveedores</span>
                        </a>
                    </li>
                </ul>


            </div>

            <div class="bottom-content">
                <li class="nav-link">
                    <a href="../../php/controladorCerrar.php">
                        <i class='bx bx-log-out-circle icon'></i>
                        <span class="text nav-text">Cerrar Sesión</span>
                    </a>
                </li>

            </div>
        </div>
    </nav>
