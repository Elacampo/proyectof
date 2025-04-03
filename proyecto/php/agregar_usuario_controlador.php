<?php
include('conecta.php');



if(isset($_POST['btnEnviar'])){

    $nombre = ucfirst(strtolower((trim($_POST['nombre']))));
    $apellido = ucfirst(strtolower((trim($_POST['apellido']))));
    $correo = trim($_POST['correo']);
    $rol = $_POST['rol'];
    $psw = trim($_POST['password']);
    $password_hash = password_hash($psw, PASSWORD_BCRYPT);

    // Verificar si el correo electrónico ya está en uso
    $sql_verificar_correo = "SELECT * FROM usuarios WHERE email = ?";
    $stmt_verificar_correo = mysqli_prepare($conectar, $sql_verificar_correo);
    mysqli_stmt_bind_param($stmt_verificar_correo, "s", $correo);
    mysqli_stmt_execute($stmt_verificar_correo);
    $resultado_verificar_correo = mysqli_stmt_get_result($stmt_verificar_correo);
    $fila_verificar_correo = mysqli_fetch_assoc($resultado_verificar_correo);

// Verificar si se encontró algún resultado
if(mysqli_num_rows($resultado_verificar_correo) > 0) {
    // El correo electrónico ya está en uso, mostrar mensaje de error
    echo "<script>
            Swal.fire({
                title: 'Correo ya en uso!',
                text: 'El correo electrónico ingresado ya está asociado a otra cuenta.',
                icon: 'error'
            });   
          </script>";
} else {
    // El correo electrónico no está en uso, proceder con la inserción del usuario
    // Preparar la consulta para insertar el nuevo usuario
    $sql_insertar_usuario = "INSERT INTO usuarios(nombre, apellido, email, id_rol, password, activo) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt_insertar_usuario = mysqli_prepare($conectar, $sql_insertar_usuario);
    
    if($stmt_insertar_usuario) {
        // Asociar parámetros y ejecutar la consulta
        mysqli_stmt_bind_param($stmt_insertar_usuario, "sssis", $nombre, $apellido, $correo, $rol, $password_hash);
        $resultado_insertar_usuario = mysqli_stmt_execute($stmt_insertar_usuario);
        
        if($resultado_insertar_usuario){
            echo "<script>
                        Swal.fire({
                            title: 'Usuario creado!',
                            text: 'El usuario se ha creado correctamente!',
                            icon: 'success',
                            didClose: () => { 
                                window.location.href = '../admin/usuarios.php';
                            }
                        });   
                    </script>";
        } else {
            // Error al crear el usuario
            echo "<script>
                    Swal.fire({
                        title: 'Usuario no creado!',
                        text: 'El usuario no se ha creado correctamente!',
                        icon: 'error'
                    });   
                </script>";
        }
        mysqli_stmt_close($stmt_insertar_usuario);
    } else {
        // Error al preparar la consulta
        echo "Error al preparar la consulta: " . mysqli_error($conectar);
    }
}

mysqli_close($conectar);
}
?>