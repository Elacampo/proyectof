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

        // Consulta para obtener el nombre del proveedor si no es NULL
        if($row['proveedor'] !== null){
            $sqlProveedor = "SELECT nombre FROM proveedores WHERE proveedor_id = ". $row['proveedor'];
            $resultProveedor = $conectar->query($sqlProveedor);
            $proveedor = $resultProveedor->fetch_assoc()['nombre'];
        } else {
            $proveedor = 'Sin proveedor';
        }
        // Consulta para obtener el nombre de la categoria
        $sqlCategoria = "SELECT nombre FROM categoria WHERE id_cat = ". $row['categoria'];
        $resultCategoria = $conectar->query($sqlCategoria);
        $categoria = $resultCategoria->fetch_assoc()['nombre'];

        if($row['ubicacion'] === NULL){
            $area = '<h4 class="noStockAsignado">No asignada</h4> ';
        } else{
            // Consulta para obtener el nombre de la ubicacion
            $sqlArea = "SELECT area FROM espacio WHERE id_bodega = '" . $row['ubicacion'] . "'";
            $resultArea = $conectar->query($sqlArea);
            $area = $resultArea->fetch_assoc()['area'];
        }

        // Consultar para obtener el stock actual
        $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
        $resultStock = $conectar->query($sqlStock);
        $stock_row = $resultStock->fetch_assoc();
        

       // Verificar si el producto está en la tabla stock y obtener el stock actual
        if ($stock_row['stock_total'] !== null) {
            $stock_actual = $stock_row['stock_total'];
            // Consultar para comprobar si el producto está en stock
            $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = " . $row['id'];
            $resultado_stock = $conectar->query($sql_comprobar_stock);

            if ($resultado_stock->num_rows > 0) {
                if ($stock_actual == 0) {
                    $stock_actual = '<h4 class="fueraStock">Agotado</h4>';
                }
            } else {
                $stock_actual = '<h4 class="noStockAsignado" style = "padding: 10px 5px ;">No asignado</h4>';
            }
        } else {
            $stock_actual = '<h4 class="noStockAsignado" style = "padding: 25px 10px ;"  >No asignado</h4>';
        }



        $today = date('Y-m-d');
        $estado_ven = '';
        if ($row['fecha_ven'] < $today) {
            $estado_ven = '<h4 class="expirado">Expirado</h4>';
        } else{
             // Formatear la fecha al formato "dd/mm/yy"
            $estado_ven = date('d/m/y', strtotime($row['fecha_ven']));
        }

        $precio_formateado = number_format($row['precio_producto'], 3, ',', '.');

        // Construir la fila de la tabla
          $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
          $html .= '<td>' . ucfirst($row['nombre_producto']). '</td>';
          $html .= '<td> '.ucfirst($row['unidad_medida']).' </td>';
          $html .= '<td>'. $categoria .'</td>';
          $html .= '<td>'. $estado_ven .'</td>';
          $html .= '<td>'. $area.'</td>';
          $html .= '<td>'. $proveedor.'</td>';
          $html .= '<td>$'. $precio_formateado.'</td>';
          $html .= '<td>'.$stock_actual.'</td>';
          $html .= '<td>
                          <div class="iconos">
                              <a href="editar_producto.php?id='.$row['id'].'  " class="editar-icon" title="Editar"><i class="bx bxs-edit bx-sm"></i></a>
                              <a href="ver_producto.php?id='.$row['id'].'"class = "ver_mas-icon" title = "Ver más"><i class="bx bx-show-alt bx-sm" ></i></a>
                          </div> 
                      </td>';
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