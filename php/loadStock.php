<?php

require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['stock_actual', 'producto', 'ubicacion', ];
$table = "stock";

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

        // Consulta para obtener nombre del producto
        $sqlProducto = "SELECT nombre_producto FROM producto WHERE id =".$row['producto'];
        $resultProducto = $conectar -> query($sqlProducto);
        $nombre_producto = $resultProducto -> fetch_assoc()['nombre_producto'];

         // Consulta para obtener el nombre de la ubicacion
         $sqlArea = "SELECT area FROM espacio WHERE id_bodega = '" . $row['ubicacion'] . "'";
         $resultArea = $conectar->query($sqlArea);
         $area = $resultArea->fetch_assoc()['area'];

        // Construir la fila de la tabla
        $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
        $html .= '<td>' . $row['stock_actual'] . '</td>';
        $html .= '<td>' . ucfirst($nombre_producto). '</td>';
        $html .= '<td>'.$row['ubicacion'].'</td>';
        $html .= '<td>'.$area .'</td>';
        $html .= '<td>
                        <div class="iconos">
                            <a href="editar_stock.php?stockProducto='.$row['producto'].'  " class="editar-icon" title="Editar"><i class="bx bxs-edit bx-sm"></i></a>
                            <a href="ver_producto.php?id='.$row['producto'].'"class = "ver_mas-icon" title = "Ver más"><i class="bx bx-show-alt bx-sm" ></i></a>
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
