<?php
require '../../php/conecta.php';

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

$id_factura = $_GET['id_factura'];

$sql_factura = $conectar ->prepare ("SELECT * from factura WHERE id_factura = ?");
$sql_factura ->bind_param("i",$id_factura);
$sql_factura ->execute();
$result_factura = $sql_factura ->get_result();
$factura_resultado = $result_factura ->fetch_assoc();

$cliente_id = $factura_resultado['id_cliente'];
$sql_cliente = $conectar ->prepare ("SELECT nombre ,apellido from usuarios WHERE id = ?");
$sql_cliente ->bind_param("i",$cliente_id);
$sql_cliente ->execute();
$result_cliente = $sql_cliente ->get_result();
$cliente_resultado = $result_cliente ->fetch_assoc();
$nombre_cliente = $cliente_resultado['nombre'];
$apellido_cliente = $cliente_resultado['apellido'];


$hoy = date('Y-m-d');
$fecha_emision = date('d/m/y', strtotime($factura_resultado['fecha_emision']));
$fecha_ven = date('d/m/y', strtotime($factura_resultado['fecha_limite_validez']));


// Consulta para obtener los productos de la factura
$sql_productos = $conectar->prepare("SELECT * FROM detalles_factura WHERE id_factura = ?");
$sql_productos->bind_param("i", $id_factura);
$sql_productos->execute();
$result_productos = $sql_productos->get_result();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Factura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>


<style>
@page {
    size: auto;
    margin: 0mm;
}

@media print {

    header,
    footer {
        display: none !important;
    }
}
</style>

<body>



    <!-- Invoice -->

    <div id="factura-imprimir" class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="sm:w-full lg:w-full mx-auto">
            <!-- Card -->
            <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl">

                <!-- Grid -->
                <div class="flex justify-between">
                    <div>
                        <svg class="size-10" width="26" height="26" viewBox="0 0 26 26" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1 26V13C1 6.37258 6.37258 1 13 1C19.6274 1 25 6.37258 25 13C25 19.6274 19.6274 25 13 25H12"
                                class="stroke-blue-600" stroke="currentColor" stroke-width="2" />
                            <path
                                d="M5 26V13.16C5 8.65336 8.58172 5 13 5C17.4183 5 21 8.65336 21 13.16C21 17.6666 17.4183 21.32 13 21.32H12"
                                class="stroke-blue-600" stroke="currentColor" stroke-width="2" />
                            <circle cx="13" cy="13.0214" r="5" fill="currentColor" class="fill-blue-600" />
                        </svg>

                        <h1 class="mt-2 text-lg md:text-xl font-semibold text-blue-600">Farmacia Barranquilla</h1>
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">Factura #</h2>
                        <span class="mt-1 block text-gray-500"><?= $factura_resultado['id_factura']; ?></span>

                        <address class="mt-4 not-italic text-gray-800">
                            53, Cra. 5 Sur, Nte. Centro Historico<br>
                            Barranquilla, Atlántico<br>
                            Colombia<br>
                        </address>
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="mt-8 grid sm:grid-cols-2 gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Factura para:</h3>
                        <h3 class="text-lg font-semibold text-gray-800">
                            <?= ucfirst($nombre_cliente)," ", ucfirst($apellido_cliente) ?></h3>
                    </div>
                    <!-- Col -->

                    <div class="sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Fecha de la factura:</dt>
                                <dd class="col-span-2 text-gray-500"><?= $fecha_emision?></dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Fecha de vencimiento:</dt>
                                <dd class="col-span-2 text-gray-500"><?= $fecha_ven?></dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Table -->
                <div class="mt-6">
                    <div class="border border-gray-200 p-4 rounded-lg space-y-4">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="sm:col-span-2 text-xs font-medium text-gray-500 uppercase">Producto</div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase">Precio Unitario
                            </div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase">Cantidad</div>
                            <div class="text-end text-xs font-medium text-gray-500 uppercase">Monto</div>
                        </div>

                        <div class="hidden sm:block border-b border-gray-200"></div>

                        <?php
                        $precio_total = 0;

                        foreach($result_productos as $producto) :
                        $sql_producto_nombre = $conectar ->prepare ("SELECT nombre_producto from producto WHERE id = ?");
                        $sql_producto_nombre ->bind_param("s",$producto['id_producto']);
                        $sql_producto_nombre ->execute();
                        $result_producto_nombre = $sql_producto_nombre ->get_result();
                        $nombre_producto_resultado = $result_producto_nombre ->fetch_assoc();

                        $monto_producto = $producto['cantidad'] * $producto['precio'];
                        $precio_total += $monto_producto;

                        $precio_formateado = number_format($producto['precio'], 0, ',', '.');
                        $precio_total_formateado = number_format($precio_total, 0, ',', '.');
                        $monto_producto_formateado = number_format($monto_producto, 0, ',', '.');
        
                
                        ?>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                            <div class="col-span-full sm:col-span-2">
                                <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Producto</h5>
                                <p class="font-medium text-gray-800">
                                    <?= $nombre_producto_resultado['nombre_producto']?>
                                </p>
                            </div>

                            <div>
                                <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Precio unitario
                                </h5>
                                <p class="text-gray-800"><?= $precio_formateado?></p>
                            </div>

                            <div>
                                <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Cantidad</h5>
                                <p class="text-gray-800"><?= $producto['cantidad']?></p>
                            </div>


                            <div>
                                <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Monto</h5>
                                <p class="sm:text-end text-gray-800"><?= $monto_producto_formateado?></p>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <!-- Fin Producto 1 -->
                    </div>
                </div>
                <!-- End Table -->

                <!-- Flex -->
                <div class="mt-8 flex sm:justify-end">
                    <div class="w-full max-w-2xl sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Monto estimado a pagar:</dt>
                                <dd class="col-span-2 text-gray-500">$ <?= $precio_total_formateado?> COP</dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
                <!-- End Flex -->

                <div class="mt-8 sm:mt-12">
                    <h4 class="text-lg font-semibold text-gray-800">Gracias!</h4>
                    <p class="text-gray-500">Si tienes alguna pregunta relacionada con esta factura, utiliza la
                        siguiente información de contacto:</p>
                    <div class="mt-2">
                        <p class="block text-sm font-medium text-gray-800">farmaciaBQ@gmail.com</p>
                        <p class="block text-sm font-medium text-gray-800">+57 300 3958741</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-gray-500">© 2024</p>
            </div>
            <!-- End Card -->

            <!-- Buttons -->
            <div id="botones" class="mt-6 flex justify-end gap-x-3">
                <a href="facturas.php">
                    <button
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
                        </svg>
                        Atrás
                    </button>
                </a>

                <button onclick="imprimir()"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Imprimir / Generar PDF
                </button>
            </div>
            <!-- End Buttons -->
        </div>
    </div>
    <!-- End Invoice -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
    var botones = document.getElementById("botones");

    function imprimir() {
        botones.style.display = "none";
        window.print();
        botones.style.display = "flex";
    }

    window.onload = function() {
        document.getElementById("generar-pdf").addEventListener("click", () => {
            const factura = this.document.getElementById("factura-imprimir");
            var opt = {
                margin: [-1, 0.1, 0.1, 0.1],
                filename: 'factura.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 1,
                    dpi: 300,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait',
                    dpi: 300
                }
            };
            html2pdf().from(factura).set(opt).save();
        })
    }
    </script>

</body>

</html>