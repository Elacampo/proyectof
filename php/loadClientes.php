<?php
require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['activo','email', 'nombre', 'id', 'apellido'];
$table = "usuarios";

// Construir la cláusula WHERE según el campo de búsqueda
$where = 'WHERE id_rol = 3';
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

        //Determinar el logo para el estado activo
        $activoLogo='';
        switch($row['activo']){
            case 1:
                $activoLogo = "<div class='ring-container'><div class='ringring'></div><div class='circle'></div></div>";
                $accionBoton = '<a href="../../php/desactivar_usuario.php?id='.$row['id'].'" onclick="alertaEliminar(event)" class="desactivar-icon" title="Desactivar"><i class="bx bxs-user-x bx-sm" ></i></i></a>';
                break;
            case 0:
                $activoLogo = "<div class='ring-container'><div class='ringring ring_inactive'></div><div class='circle circle_inactive'></div></div>";
                $accionBoton = '<a href="../../php/activar_usuario.php?id='.$row['id'].'" onclick="alertaRestablecer(event)" class="activar-icon" title="Activar"><i class="bx bxs-user-check bx-sm" ></i></a>';
                break;
        }

        // Construir la fila de la tabla
        $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
        $html .= '<td class="estado"> ' . $activoLogo.'</td>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['email'] . '</td>';
        $html .= '<td>' . ucfirst($row['nombre']) . '</td>';
        $html .= '<td>' . ucfirst($row['apellido']) . '</td>';     
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
