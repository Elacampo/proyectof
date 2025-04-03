<?php

if (!empty($_POST['btn_ingresar'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $sql = $conectar->query("SELECT * FROM usuarios WHERE email = '$email' ");
        
        if ($datos = $sql->fetch_object()) {
            if (password_verify($password, $datos->password)) {
                if ($datos->activo == 1) { 
                    $_SESSION["id"] = $datos->id;
                    $_SESSION["nombre"] = $datos->nombre;
                    $_SESSION["apellido"] = $datos->apellido;
                    $_SESSION['rol'] = $datos->id_rol;

                    if ($_SESSION['rol'] == 1) {
                        header("location:../pages/admin/admin.php");
                    } else if ($_SESSION['rol'] == 2) {
                        header("location:../pages/empleado/farmaceutico.php");
                    } else if ($_SESSION['rol'] == 3) {
                        header("location:../pages/cliente/cliente.php");
                    }
                } else {
                    echo "<div style='background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem;'>
                    Cuenta inactiva. Contacta al administrador para activar tu cuenta.
                    </div>";
                }
            } else {
                echo "<div style='background-color: #fff3cd; color: #856404; border-color: #ffeeba; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem; display:flex; align-items:center;'>
                <i class='bx bxs-error bx-md'></i>
                Contraseña incorrecta. Verifica e intenta nuevamente.
                </div>";
            }
        } else {
            echo "<div style='background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem;'>
            Credenciales incorrectas. Verifica e intenta nuevamente.
            </div>";
        }
    } else {
        echo "<div style='background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem;'>
        Uno o ambos campos están vacíos.  
        </div>";
    }
}

?>

