<?php

require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['id_factura', 'id_cliente', 'fecha_emision', 'fecha_limite_validez',
'estado_factura'];
$table = "factura";

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

         //Consulta nombre y apellido cliente
         $sql_cliente = "SELECT nombre, apellido FROM usuarios WHERE id = ".$row['id_cliente'];
         $resultCliente = $conectar -> query($sql_cliente);
         $cliente = $resultCliente ->fetch_assoc();
         $nombre_cliente = $cliente['nombre'];
         $apellido_cliente = $cliente['apellido'];

        $hoy = date('Y-m-d');

        $fecha_emision = date('d/m/y', strtotime($row['fecha_emision']));
        $fecha_ven = date('d/m/y', strtotime($row['fecha_limite_validez']));

        switch($row['estado_factura']){
            case 'Pagado':
                $estado = '<h4 class = "pagado">Pagado</h4>';
                break;
            case 'Pendiente':
                $estado = '<h4 class = "pendiente">Pendiente</h4>';
                break;
            case 'Vencida';
            $estado = '<h4 class = "vencida"> Vencida</h4>';
                break;
            case 'Rechazada';
            $estado = '<h4 class = "rechazada">Rechazada</h4>';
                break;
        }

        

        // Construir la fila de la tabla
        $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
        $html .= '<td>' . $row['id_factura'] . '</td>';
        $html .= '<td>' . $nombre_cliente.' '.$apellido_cliente. '</td>';
        $html .= '<td>'. $fecha_emision.'</td>';
        $html .= '<td>'.$fecha_ven .'</td>';
        $html .= '<td>'.$estado.'</td>';

        $html .= '<td>
                        <div class="iconos">
                            <button aria-label="Cambiar Estado" title = "Cambiar Estado Factura"class="openModalBtn" data-id-factura="' . $row['id_factura'] . '">
                            <i class="bx bx-transfer bx-sm" data-id-factura="' . $row['id_factura'] . '"></i>
                            </button>
                            <a href="ver_factura.php?id_factura='.$row['id_factura'].'"class = "ver_mas-icon" title = "Ver más"><i class="bx bx-show-alt bx-sm" ></i></a>
                        </div> 
                  </td>';
        $html .= '</tr>';
    }
} else {
    
    $html .= '<tr>';
    $html .= '<td colspan="6">Sin resultados</td>';
    $html .= '<tr>';
}

// Devolver los resultados como JSON
echo json_encode(array('html' => $html, 'totalPages' => $totalPages), JSON_UNESCAPED_UNICODE);

// Cerrar la conexión a la base de datos
$conectar->close();
?>
