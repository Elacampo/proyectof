<?php
    include('conecta.php');

    if(isset($_POST['btnEnviar'])){
        $id = ucfirst($_POST['id_bodega']);

        $nuevo_id = ucfirst($_POST['id_nuevo_bodega']);
        $area = ucfirst($_POST['area']); 
        $cap_max = $_POST['cap_max'];
        $estado = $_POST['estado'];

        // Verificar si el área o el nombre de la bodega ya existe en la base de datos
        $verificar_sql = "SELECT * FROM espacio WHERE (area='$area' OR id_bodega='$nuevo_id') AND id_bodega != '$id'";
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);

        if(mysqli_num_rows($verificar_resultado) > 0) {
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar el espacio!',
                    text: 'El área o nombre de bodega ya existe en el sistema!',
                    icon: 'error'
                });   
            </script>";
        } else {
            // Si el área o nombre de bodega no existe, procede con la actualización
            $sql = "UPDATE espacio SET area='$area', capacidad_max='$cap_max', estado = '$estado', id_bodega = '$nuevo_id' WHERE id_bodega= '$id' ";

            $resultado = mysqli_query($conectar, $sql);

            if($resultado){
                echo "<script>
                    Swal.fire({
                        title: 'Se ha actualizado el espacio!',
                        text: 'Los datos se han actualizado correctamente!',
                        icon: 'success',
                        didClose: () => { 
                            window.location.href = '../admin/espacio.php';
                        }
                    });   
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'No se ha actualizado el espacio!',
                        text: 'Los datos no se han podido actualizar correctamente!',
                        icon: 'error'
                    });
                    window.location.assign('../pages/admin/espacio.php');
                </script>";
            }
        }
        mysqli_close($conectar);
    } else {
        $id = $_GET['id_bodega'];
        $sql = "SELECT * FROM espacio WHERE id_bodega='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);

        $area = $row['area'];
        $cap_max = $row['capacidad_max'];

        $estado = $row['estado'];

        $stock_query = "SELECT s.stock_actual, p.nombre_producto
                    FROM stock s
                    INNER JOIN producto p ON s.producto = p.id
                    WHERE s.ubicacion = '$id'";
        $stock_resultado = mysqli_query($conectar, $stock_query);
        $stock_row = mysqli_fetch_assoc($stock_resultado);

        // Verificar si las variables tienen valor antes de asignarlas
        $cantidad_stock = isset($stock_row['stock_actual']) ? $stock_row['stock_actual'] : 0;
        $nombre_producto = isset($stock_row['nombre_producto']) ? $stock_row['nombre_producto'] : "No asignado";

        mysqli_close($conectar);
    }
?>
