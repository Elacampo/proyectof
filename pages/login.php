<?php 

session_start();

if(empty($_SESSION['id'])){
    if(basename($_SERVER['PHP_SELF']) != 'login.php') {
        header("location: login.php");
        exit;
    }
}

if(isset($_SESSION['rol'])) {
    if($_SESSION['rol'] == 1){
        header("location: admin/admin.php");
        exit;
    }

    if($_SESSION['rol'] == 2){
        header("location: empleado/farmaceutico.php");
        exit;
    }

    if($_SESSION['rol'] == 3){
        header("location: cliente/cliente.php");
        exit;
    }
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<title>Inicio de sesión</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css?v=<?php echo filemtime('../css/login.css'); ?>">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="../img/wave.png">
	<div class="container">
	<div class="i" id="casa">
            <a href="../index.php"><i class='bx bxs-home bx-lg'></i></a>
        </div>  
		<div class="img">
			<img src="../img/Pharmacist-bro.svg">
		</div>
		<div class="login-content">
			<form action="" method="post" id="formulario">
				<img src="../img/avatar.svg">
				<h2 class="title">Inicia Sesión</h2>
				<?php
				include '../php/conecta.php';	
				include "../php/controladorLogin.php";
				?>

				<div class="grupo_correo">
				<div class="input-div one">
           		   <div class="i">
						<i class='bx bxs-user'></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Email</h5>
           		   		<input type="text" name="email" class="input" id="correo" >
           		   </div>
           		</div>

				</div>
           		
           		<div class="input-div pass">
           		   <div class="i"> 
						<i class='bx bxs-lock-alt'></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Contraseña</h5>
           		    	<input type="password" name="password" class="input" id="contra">
            	   </div>
            	</div>
            	<input type="submit" class="btn" name= "btn_ingresar"value="Iniciar Sesión">
				<div class="to-rgs">
					<h5>¿No tienes una cuenta?</h5>
					<a href="../pages/registro.php">Registrate Aqui</a>
				</div>
				
            </form>
        </div>
    </div>
    <script type="text/javascript" src="../js/login_inicio.js"></script>
</body>
</html>
