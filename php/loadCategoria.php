<?php
session_start();
require 'conecta.php';

// Verificar la existencia del campo "campo" en la solicitud POST
$campo = isset($_POST['campo']) ? $conectar->real_escape_string($_POST['campo']) : null;

// Definir las columnas y la tabla
$columns = ['id_cat', 'nombre', 'activo']; //Cambiar AQUI
$table = "categoria"; // Cambiar AQUI

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

            $estado = '';
            switch ($row['activo']) {
                case 0:
                    $estado = 'Inactivo';
                    break;
                case 1:
                    $estado = 'Activo';
                    break;
            }

        //Determinar el logo para el estado activo
        $activoLogo='';
        switch($row['activo']){
            case 1:
                $activoLogo = "<div class='ring-container'><div class='ringring'></div><div class='circle'></div></div>";
                $accionBoton = '<a href="../../php/desactivar_categoria.php?id_cat='.$row['id_cat'].'" onclick="alertaEliminar(event)" class="desactivar-icon" title="Desactivar"><i class="bx bx-x-circle bx-sm"></i></a>';
                break;
            case 0:
                $activoLogo = "<div class='ring-container'><div class='ringring ring_inactive'></div><div class='circle circle_inactive'></div></div>";
                $accionBoton = '<a href="../../php/activar_categoria.php?id_cat='.$row['id_cat'].'" onclick="alertaRestablecer(event)" class="activar-icon" title="Activar"><i class="bx bx-check bx-sm"></i></a>';
                break;
        }

        // Construir la fila de la tabla
        $html .= '<tr class="' . ($count % 2 == 0 ? 'par' : 'impar') . '">';
        $html .= '<td>' . $row['id_cat'] . '</td>';
        $html .= '<td>' . ucfirst($row['nombre']). '</td>';
        $html .= '<td>' . $estado . '</td>';
        if($_SESSION['rol'] == 1 ){   
            $html .= '<td>
                            <div class="iconos">
                                <a href="../../pages/admin/editar_categoria.php?id_cat='.$row['id_cat'].'  " class="editar-icon" title="Editar"><i class="bx bxs-edit bx-sm"></i></a>'
                                . $accionBoton . // Aquí se inserta el botón de activar o desactivar según el estado del usuario
                        '   </div> 
                        </td>';
            $html .= '</tr>';
        } 
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
