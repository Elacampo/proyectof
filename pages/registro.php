<!DOCTYPE html>
<html>
<head>
	<title>Registrate</title>
	<script src="sweetalert2.all.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/registro.css?v=<?php echo filemtime('../css/registro.css'); ?>">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php 

include '../php/conecta.php';

//validar que exista el boton	

if(isset($_POST['btn_registrar'])){
	$mensaje = "";
	$correo = $conectar->real_escape_string($_POST['email']);
	$nombre = $conectar->real_escape_string($_POST['nombre']);
	$apellido = $conectar->real_escape_string($_POST['apellido']);
	$password = $conectar->real_escape_string($_POST['password']);
	$ver_psw = $conectar->real_escape_string($_POST['verificar_psw']);

	//Consultar que el registro no exista
	$validar_correo = "SELECT * FROM usuarios WHERE email = '$correo'";
	$validando_correo = $conectar -> query($validar_correo);
	
	if($validando_correo -> num_rows > 0){
		echo "<script>
			Swal.fire({
				position: 'center',
				icon: 'error',
				title: 'El correo ya se encuentra registrado! Vuelve a intentarlo',
				confirmButtonText:'Ok'
			});
		</script>";
	}
	else{
		
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		//Consulta insertar datos
		$insertar = "INSERT INTO usuarios(email, nombre, apellido, password,id_rol,activo) VALUES('$correo','$nombre','$apellido','$password_hash','3','1')";
		$guardando = $conectar -> query($insertar);

		if($guardando > 0){
				echo "
				<script>
				Swal.fire({
					position: 'center',
					icon: 'success',
					title: 'La cuenta se ha registrado con exito!',
					confirmButtonText:'Ok'
				});
				</script>";
			} else{
				echo "error en el registro";
			}
	}
}
?>


	<img class="wave" src="../img/wave.png">
	<div class="container">
        
        <div class="i" id="casa">
            <a href="../index.php"><i class='bx bxs-home bx-lg'></i></a>
        </div>  
		<div class="img">   
			<img src="../img/Pharmacist-bro.svg">
		</div>
		<div class="login-content">
			<form method= "post" action="<?php echo $_SERVER['PHP_SELF'];?>" id="formulario" >
                <div class="titulos">
                    <img src="../img/avatar.svg"> 
				    <h2 class="title">Registrate</h2>
				</div>
				
				<div class="cajaEmail">
					<div class="input-div one correo" id="box-email">
						<div class="i">
						  <i class='bx bxs-user'></i>
						</div>
						<div class="div">
								<h5>Email</h5>
								<input type="email" class="input" name = "email">
						  <i class='bx bx-error-circle bx-sm icono-alerta'></i>
						</div>
					</div>
					 <h5 class="aviso-email">Email Invalido</h5>

				</div>
           		

                <div class="name-cel">

					<div class="cajaNombre">
						<div class="input-div name">
							<div class="i name-icon">		
								<i class='bx bxs-id-card'></i>
							</div>
							<i class='bx bx-error-circle bx-sm icono-alerta-name'></i>
							<div class="div">
								<h5>Nombre</h5>
								<input type="text" class="input" name ="nombre">	
							</div>
						</div>
						<h5 class="aviso-nombre">Nombre Invalido</h5>
					</div>
                  
					<div class="cajaApellido">
						<div class="input-div name">
							<div class="i"><i class='bx bxs-id-card'></i></div>
							<div class="div">
								<h5>Apellido</h5>
								<input type="text" class="input" name="apellido">
								<i class='bx bx-error-circle bx-sm icono-alerta-lastname'></i>
							</div>
						</div>
						<h5 class="aviso-apellido">Apellido Invalido</h5>
					</div>
                    
                </div>


				<div class="cajaPsw">
					<div class="input-div pass" id="box-psw">
						<div class="i"> 
						  <i class='bx bxs-lock-alt'></i>
						</div>
						<div class="div">
							 <h5>Contraseña</h5>
							 <input type="password" class="input" name="password" id="password">
						  <i class='bx bx-error-circle bx-sm icono-alerta-psw'></i>
					 </div>
				  </div>
				  <h5 class="aviso-psw">Contraseña no valida.</h5>
				</div>	
           		


				<div class="cajaPsw_verificar">
					<div class="input-div pass">
						<div class="i"> 
						  <i class='bx bxs-lock-alt'></i>
						</div>
						<div class="div">
							 <h5>Confirmar contraseña</h5>	
							 <input type="password" class="input" name ="verificar_psw" id="password_dos">
						<i class='bx bx-error-circle bx-sm icono-alerta-verificar'></i>
					 </div>
				  </div>
				  <h5 class="aviso-verificar">Ambas contraseñas deben ser iguales</h5>	
				</div>
                
            	<input type="submit" class="btn" value="Registrate" name="btn_registrar">
				<div class="alerta-campo">Por favor, completa todos los campos correctamente.</div>
				<div class="to-rgs">
					<h5>¿Ya tienes una cuenta?</h5>
					<a href="../pages/login.php">Inicia sesión</a>
					
				</div>
				<div class="exito-campo">Cuenta creada con exito.</div>
				

            </form>
        </div>
    </div>
    <script type="text/javascript" src="../js/registro.js"></script>
	<script src="../js/validar_register.js"></script>
</body>
</html>
