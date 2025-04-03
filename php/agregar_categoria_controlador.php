<?php
include('conecta.php');

if(isset($_POST['btnEnviar'])){

    $nombre = ucfirst(strtolower((trim($_POST['nombre']))));

    // Verificar si el nombre ya está en uso
    $sql_verificar_nombre = "SELECT * FROM categoria WHERE nombre = ?";
    $stmt_verificar_nombre = mysqli_prepare($conectar,$sql_verificar_nombre);
    mysqli_stmt_bind_param($stmt_verificar_nombre, 's' , $nombre);
    mysqli_stmt_execute($stmt_verificar_nombre);
    $resultado_verificar_nombre = mysqli_stmt_get_result($stmt_verificar_nombre);
    $fila_verificar_nombre = mysqli_fetch_assoc($resultado_verificar_nombre);

// Verificar si se encontró algún resultado
if(mysqli_num_rows($resultado_verificar_nombre) > 0) {
    // El nombre ya está e uso, mostrar mensaje de error
    echo "<script>
            Swal.fire({
                title: 'Nombre ya en uso!',
                text: 'El nombre de la categoria ingresada ya existe.',
                icon: 'error'
            });   
          </script>";
} else {
    // Preparar la consulta para insertar el nuevo usuario
    $sql_insertar_categoria = "INSERT INTO categoria(nombre) VALUES (?)";
    $stmt_insertar_categoria = mysqli_prepare($conectar, $sql_insertar_categoria);
    
    if($stmt_insertar_categoria) {
        // Asociar parámetros y ejecutar la consulta
        mysqli_stmt_bind_param($stmt_insertar_categoria, "s", $nombre);
        $resultado_insertar_categoria = mysqli_stmt_execute($stmt_insertar_categoria);
        
        if($resultado_insertar_categoria){
            echo "<script>
                        Swal.fire({
                            title: 'Categoria creada!',
                            text: 'La categoria se ha creado correctamente!',
                            icon: 'success',
                            didClose: () => { 
                                window.location.href = '../admin/categoria.php';
                            }
                        });   
                    </script>";
        } else {
            // Error al crear el usuario
            echo "<script>
                    Swal.fire({
                        title: 'Categoria no creada!',
                        text: 'La categoria no se ha creado correctamente!',
                        icon: 'error'
                    });   
                </script>";
        }
        mysqli_stmt_close($stmt_insertar_categoria);
    } else {
        // Error al preparar la consulta
        echo "Error al preparar la consulta: " . mysqli_error($conectar);
    }
}
mysqli_close($conectar);
}
?>