<?php

require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['id','nombre_producto','codigo','descripcion','categoria', 'fecha_ven','ubicacion','proveedor','unidad_medida','fecha_ingreso','imagen_producto','precio_producto']; 
$table = "producto";

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

        // Consultar para obtener el stock actual
        $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
        $resultStock = $conectar->query($sqlStock);
        $stock_row = $resultStock->fetch_assoc();

        $stock_actual = $stock_row['stock_total'] ?? 0;

        $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = " . $row['id'];

        $resultado_stock = mysqli_query($conectar, $sql_comprobar_stock);
        if(mysqli_num_rows($resultado_stock) > 0 && $stock_actual == 0){
            // Consulta para obtener el nombre de la categoria
            $sqlCategoria = "SELECT nombre FROM categoria WHERE id_cat = ". $row['categoria'];
            $resultCategoria = $conectar->query($sqlCategoria);
            $categoria = $resultCategoria->fetch_assoc()['nombre'];
    
            // Consulta para obtener el nombre de la ubicacion
            $sqlArea = "SELECT area FROM espacio WHERE id_bodega = '" . $row['ubicacion'] . "'";
            $resultArea = $conectar->query($sqlArea);
            $area = $resultArea->fetch_assoc()['area'];

            // Construir la fila de la tabla
              $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
              $html .= '<td>' . ucfirst($row['nombre_producto']). '</td>';
              $html .= '<td> '.ucfirst($row['unidad_medida']).' </td>';
              $html .= '<td>'. $categoria .'</td>';
              $html .= '<td>'. $area.'</td>';   
              $html .= '<td>$'. $row['precio_producto'].'</td>';
              $html .= '<td> <h4 class="fueraStock">Agotado</h4> </td>';
              $html .= '<td>
                              <div class="iconos">
                              <a href="ver_producto.php?id='.$row['id'].'"class = "ver_mas-icon" title = "Ver más"><i class="bx bx-show-alt bx-sm" ></i></a>
                              </div> 
                          </td>';
              $html .= '</tr>';
        }
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
