<?php
session_start();
require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['id_bodega','area','estado','capacidad_max' ]; //Cambiar AQUI
$table = "espacio";

// Construir la cláusula WHERE según el campo de búsqueda
$where = '';
if ($campo != null) {
    $where = "WHERE (";
    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }

    $where = substr_replace($where, "", -3);
    $where .= ")";
}

// Obtener el límite y la página de la solicitud POST
$limit = isset($_POST['registros']) ? $conectar->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conectar->real_escape_string($_POST['pagina']) : 1;

// Validar la página para evitar valores negativos
if ($pagina < 1) {
    $pagina = 1;
}

// Calcular el inicio de los resultados según la página y el límite
$inicio = ($pagina - 1) * $limit;

// Construir la cláusula LIMIT
$sLimit = "LIMIT $inicio, $limit";

// Consulta para contar el total de registros
$sqlCount = "SELECT COUNT(*) AS total FROM $table $where";
$resultCount = $conectar->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalPages = ceil($rowCount['total'] / $limit); // Calcular el número total de páginas

// Consulta para obtener los datos de la página actual
$sql = "SELECT " . implode(", ", $columns) . " FROM $table $where $sLimit";
$resultado = $conectar->query($sql);

$num_rows = $resultado->num_rows;

// Inicializar la variable para almacenar el HTML de los resultados
$html = '';

// Construir la tabla HTML con los resultados
if ($num_rows > 0) {
    $count = 0;
    while ($row = $resultado->fetch_assoc()) {
        $count++;

        $id_bodega = $row['id_bodega'];
        $stock_query = "SELECT SUM(stock_actual) AS total_stock FROM stock WHERE ubicacion = '$id_bodega'";
        $stock_resultado = $conectar->query($stock_query);
        $stock_row = $stock_resultado->fetch_assoc();

        $cantidad_stock = $stock_row['total_stock'] ?? 0;

       

        //Determinar el logo para el estado
        $estadoLogo='';
        switch($row['estado']){
            case 'Disponible':
                $estadoLogo = "<div title='Disponible'><i class='bx bxs-plus-circle bx-sm'></i></div>";
                break;
            case 'Ocupado':
                $estadoLogo = "<div title='Ocupado'><i class='bx bx-package bx-sm'></i></div>";
                break;
            case 'En mantenimiento':
                $estadoLogo = "<div title ='En mantenimiento'><i class='bx bxs-error bx-sm'></i></div>";
                break;
            case 'Completo':
                $estadoLogo = "<div title = 'Lleno'><i class='bx bxs-error-alt bx-sm'></i></div>";
                break;
            case 'Inactivo':
                $estadoLogo = "<div title = 'Inactivo'><i class='bx bx-pause bx-sm'></i></i></div>";
                break;
        }   

        // Construir la fila de la tabla
        $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
        $html .= '<td class ="estadoLogo">' . $estadoLogo . '</td>';
        $html .= '<td>' . $row['id_bodega']. '</td>';
        $html .= '<td>' . ucfirst($row['area']). '</td>';
        $html .= '<td style= "width: 245px"> ' . $row['capacidad_max']. '</td>';
        $html .= '<td>'. $cantidad_stock.'</td>';
        if($_SESSION['rol'] == 1 ){   
            $html .= '<td>
                            <div class="iconos">
                                <a href="../../pages/admin/editar_espacio.php?id_bodega='.$row['id_bodega'].'  " class="editar-icon" title="Editar"><i class="bx bxs-edit bx-sm"></i></a>
                                <a href="../../php/eliminar_espacio.php?id_bodega='.$row['id_bodega'].'" onclick="alertaEliminar(event)" class="eliminar-icon" title="Eliminar"><i class="bx bxs-trash bx-sm"></i></a>
                            </div> 
                        </td>';
        } elseif ($_SESSION['rol'] == 2){
            $html .= '<td>
                            <div class="iconos">
                                <a href="ver_espacio.php?id='.$row['id_bodega'].'"class = "ver_mas-icon" title = "Ver más"><i class="bx bx-show-alt bx-sm" ></i></a>
                            </div> 
                        </td>';

        }
        $html .= '</tr>'; 
   
    }
} else {
    // Si no hay resultados, mostrar un mensaje
    $html .= '<tr>';
    $html .= '<td colspan="6">Sin resultados</td>';
    $html .= '<tr>';
}

// Devolver los resultados como JSON
echo json_encode(array('html' => $html, 'totalPages' => $totalPages), JSON_UNESCAPED_UNICODE);

// Cerrar la conexión a la base de datos
$conectar->close();

?>
