<?php
include('conecta.php');

if(isset($_POST['btnEnviar'])){

    $nombre_bodega = trim($_POST['id_nuevo_bodega']);
    $area = ucfirst(strtolower((trim($_POST['area']))));
    $cap_max = trim($_POST['cap_max']);
    $estado = $_POST['estado'];

    // Verificar si el espacio ya existe en la base de datos
    $verificar_sql = "SELECT * FROM espacio WHERE area=? OR id_bodega=?";
    $stmt_verificar = mysqli_prepare($conectar, $verificar_sql);
    mysqli_stmt_bind_param($stmt_verificar, 'ss', $area, $nombre_bodega);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

// Verificar si se encontró algún resultado
if(mysqli_num_rows($resultado_verificar) > 0) {
    // El espacio ya existe, mostrar mensaje de error
    echo "<script>
            Swal.fire({
                title: 'Error al crear el espacio!',
                text: 'Ya existe un espacio con el mismo nombre de bodega y/o area.',
                icon: 'error'
            });   
        </script>";
} else {
    // Preparar la consulta para insertar el nuevo usuario
    $sql_insertar_espacio = "INSERT INTO espacio(area,id_bodega, capacidad_max) VALUES (?,?,?)";
    $stmt_insertar_espacio = mysqli_prepare($conectar, $sql_insertar_espacio);
    
    if($stmt_insertar_espacio) {
        // Asociar parámetros y ejecutar la consulta
        mysqli_stmt_bind_param($stmt_insertar_espacio, "ssi", $area,$nombre_bodega,$cap_max);
        $resultado_insertar_categoria = mysqli_stmt_execute($stmt_insertar_espacio);
        
        if($resultado_insertar_categoria){
            echo "<script>
                        Swal.fire({
                            title: 'Espacio creado!',
                            text: 'El espacio se ha creado correctamente!',
                            icon: 'success',
                            didClose: () => { 
                                window.location.href = '../admin/espacio.php';
                            }
                        });   
                    </script>";
        } else {
            // Error al crear el usuario
            echo "<script>
                    Swal.fire({
                        title: 'Espacio no creado!',
                        text: 'El espacio no se ha creado correctamente!',
                        icon: 'error'
                    });   
                </script>";
        }
        mysqli_stmt_close($stmt_insertar_espacio);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conectar);
    }
}
mysqli_close($conectar);
}
?>