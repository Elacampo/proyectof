<?php
    include('conecta.php');

    if(isset($_POST['btnEnviar'])){
        $id = $_POST['id_cat'];
        $nombre = ucfirst(strtolower(($_POST['nombre'])));

        // Verificar si el nombre ya existe en la base de datos
        $verificar_sql = "SELECT * FROM categoria WHERE nombre='$nombre' AND id_cat != $id";
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);

        if(mysqli_num_rows($verificar_resultado) > 0) {
            // Si el correo ya existe, muestra un mensaje de error
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar la categoria!',
                    text: 'La categoria ya se encuentra registrada en el sistema!',
                    icon: 'error'
                });   
            </script>";
        } else {
            // Si el correo no existe, procede con la actualización
            $sql = "UPDATE categoria SET nombre='$nombre' WHERE id_cat= $id ";

            $resultado = mysqli_query($conectar, $sql);

            if($resultado){
                echo "<script>
                    Swal.fire({
                        title: 'Se ha actualizado la categoria!',
                        text: 'Los datos se han actualizado correctamente!',
                        icon: 'success',
                        didClose: () => { 
                            window.location.href = '../admin/categoria.php';
                        }
                    });   
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'No se ha actualizado la categoria!',
                        text: 'Los datos no se han podido actualizar correctamente!',
                        icon: 'error'
                    });
                    window.location.assign('../pages/admin/categoria.php');
                </script>";
            }
        }
        mysqli_close($conectar);

    } else {
        $id = $_GET['id_cat'];
        $sql = "SELECT * FROM categoria WHERE id_cat='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre = $row['nombre'];


        mysqli_close($conectar);
    }
?>
