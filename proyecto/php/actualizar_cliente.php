<?php
    include('conecta.php');

    if(isset($_POST['btnActualizar'])){
        $id = $_POST['id'];
        $nombre = trim($_POST['first_name']);
        $apellido = trim($_POST['last_name']);
        $correo = trim($_POST['email']);

        // Verificar si el correo ya existe en la base de datos
        $verificar_sql = "SELECT * FROM usuarios WHERE email='$correo' AND id != $id";
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);

        if(mysqli_num_rows($verificar_resultado) > 0) {
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM usuarios WHERE id='$id'";
            $resultado = mysqli_query($conectar, $sql);
            $row = mysqli_fetch_assoc($resultado);
            $nombre_usuario = $row['nombre'];
            $apellido_usuario = $row['apellido'];
            $correo_usuario = $row['email'];
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar el usuario!',
                    text: 'El correo ya se encuentra registrado en el sistema!',
                    icon: 'error',
                });   
            </script>";
        } else {  
            $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', email='$correo' WHERE id=$id";
            $resultado = mysqli_query($conectar, $sql);

            if($resultado){
                $id = $_SESSION['id'];
                $sql = "SELECT * FROM usuarios WHERE id='$id'";
                $resultado = mysqli_query($conectar, $sql);
                $row = mysqli_fetch_assoc($resultado);
                $nombre_usuario = $row['nombre'];
                $apellido_usuario = $row['apellido'];
                $correo_usuario = $row['email'];

                echo "<script>
                    Swal.fire({
                        title: 'Se ha actualizado la información!',
                        text: 'Los datos se han actualizado correctamente!',
                        icon: 'success',
                        didClose: () => { 
                            window.location.href = '../cliente/mi-cuenta.php';
                        }
                    });   
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'No se ha actualizado el usuario!',
                        text: 'Los datos no se han podido actualizar correctamente!',
                        icon: 'error',
                        didClose: () => { 
                            window.location.href = '../cliente/mi-cuenta.php';
                        }
                    });
                </script>";
            }
        }
        mysqli_close($conectar);
    }else if (isset($_POST['btnPassword'])){
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre_usuario = $row['nombre'];
        $apellido_usuario = $row['apellido'];
        $correo_usuario = $row['email'];
        
       $new_password = $_POST['new_password'];
       $password_hash = password_hash($new_password, PASSWORD_BCRYPT);

       $sql_password = "UPDATE usuarios SET password='$password_hash' WHERE id =$id";
       $resultado_password = mysqli_query($conectar, $sql_password);

       if($resultado_password){
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre_usuario = $row['nombre'];
        $apellido_usuario = $row['apellido'];
        $correo_usuario = $row['email'];

        echo "<script>
            Swal.fire({
                title: 'Se ha actualizado la contraseña!',
                text: 'Los datos se han actualizado correctamente!',
                icon: 'success',
                didClose: () => { 
                    window.location.href = '../cliente/mi-cuenta.php';
                }
            });   
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'No se ha actualizado la contraseña!',
                text: 'Los datos no se han podido actualizar correctamente!',
                icon: 'error',
                didClose: () => { 
                    window.location.href = '../cliente/mi-cuenta.php';
                }
            });
        </script>";
    }

    }
    else{
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        $nombre_usuario = $row['nombre'];
        $apellido_usuario = $row['apellido'];
        $correo_usuario = $row['email'];

    }

?>