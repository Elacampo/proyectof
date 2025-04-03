<?php
    include('conecta.php');

    if(isset($_POST['btnEnviar'])){
        $id = $_POST['id'];
        $nombre = ucfirst(strtolower(($_POST['nombre'])));
        $apellido = ucfirst(strtolower(($_POST['apellido'])));
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        
        // Verificar si se proporcionó una nueva contraseña
        if (!empty($_POST['password'])) {
            $psw = $_POST['password'];
            $password_hash = password_hash($psw, PASSWORD_BCRYPT);
            $password_update = ", password = '$password_hash'";
        } else {
            $password_update = ""; // Mantener la contraseña existente
        }
    
        // Verificar si el correo ya existe en la base de datos
        $verificar_sql = "SELECT * FROM usuarios WHERE email='$correo' AND id != $id";
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);
    
        if(mysqli_num_rows($verificar_resultado) > 0) {
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar el usuario!',
                    text: 'El correo ya se encuentra registrado en el sistema!',
                    icon: 'error',
                });   
            </script>";
        } else {    
            $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', email='$correo', id_rol=$rol $password_update WHERE id=$id ";
    
            $resultado = mysqli_query($conectar, $sql);
    
            if($resultado){
                echo "<script>
                Swal.fire({
                    title: 'Se ha actualizado el usuario!',
                    text: 'Los datos se han actualizado correctamente!',
                    icon: 'success',
                    didClose: () => { 
                        window.location.href = '../admin/usuarios.php';
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
                    window.location.assign('../pages/admin/usuarios.php');
                </script>";
            }
        }
        mysqli_close($conectar);
    
    } else {
        $id = $_GET['id'];
        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $correo = $row['email'];
        $psw = $row['password'];
        $rol = $row['id_rol'];
    
        mysqli_close($conectar);
    }
    
?>
