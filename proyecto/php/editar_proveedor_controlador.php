<?php
    include('conecta.php');

    if(isset($_POST['btnEnviar'])){
        $id = $_POST['proveedor_id'];
        $nombre = ucfirst(($_POST['nombre']));
        $correo = $_POST['correo'];
        $celular = $_POST['celular'];

        // Verificar si el correo ya existe en la base de datos
        $verificar_sql = "SELECT * FROM proveedores WHERE correo='$correo' AND proveedor_id != $id";
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);

        if(mysqli_num_rows($verificar_resultado) > 0) {
            // Si el correo ya existe, muestra un mensaje de error
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar el usuario!',
                    text: 'El correo ya se encuentra registrado en el sistema!',
                    icon: 'error'
                });   
            </script>";
        } else {
            // Si el correo no existe, procede con la actualización
            $sql = "UPDATE proveedores SET nombre='$nombre', correo='$correo', num_celular='$celular' WHERE proveedor_id= $id ";

            $resultado = mysqli_query($conectar, $sql);

            if($resultado){
                echo "<script>
                    Swal.fire({
                        title: 'Se ha actualizado el usuario!',
                        text: 'Los datos se han actualizado correctamente!',
                        icon: 'success',
                        didClose: () => { 
                            window.location.href = '../admin/proveedores.php';
                        }
                    });   
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'No se ha actualizado el usuario!',
                        text: 'Los datos no se han podido actualizar correctamente!',
                        icon: 'error'
                    });
                    window.location.assign('../pages/admin/proveedores.php');
                </script>";
            }
        }
        mysqli_close($conectar);

    } else {
        $id = $_GET['proveedor_id'];
        $sql = "SELECT * FROM proveedores WHERE proveedor_id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre = $row['nombre'];
        $correo = $row['correo'];
        $celular = $row['num_celular'];

        mysqli_close($conectar);
    }
?>
