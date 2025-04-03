const formulario = document.getElementById('formulario');
const inputs_v = document.querySelectorAll('#formulario input');

const expresiones = {
    usuario: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo
    nombre: /^[a-zA-ZÀ-ÿ\s]{1,20}$/, // Letras y espacios, pueden llevar acentos.
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
}

const campos = {
    nombre : false,
    apellido: false,
    password : false,
    correo : false,
}
   

const iconoAlerta = document.getElementsByClassName("icono-alerta");
const iconoAlerta_psw = document.getElementsByClassName("icono-alerta-psw");
const iconoAlerta_name = document.getElementsByClassName("icono-alerta-name");
const iconoAlerta_lastname = document.getElementsByClassName("icono-alerta-lastname");
const iconoAlerta_verificar = document.getElementsByClassName("icono-alerta-verificar");
const error_correo = document.getElementsByClassName("aviso-email");
const error_psw = document.getElementsByClassName("aviso-psw");
const error_verificar = document.getElementsByClassName("aviso-verificar")
const error_nombre = document.getElementsByClassName("aviso-nombre");
const error_apellido = document.getElementsByClassName("aviso-apellido")

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "email":
            if(e.target.value === ''){
                iconoAlerta[0].classList.remove("icono-alerta-error")
                error_correo[0].classList.remove("aviso-email-error")
                campos['correo'] = false;              
            }
            else if (expresiones.correo.test(e.target.value)) {
                iconoAlerta[0].classList.remove("icono-alerta-error")
                error_correo[0].classList.remove("aviso-email-error")
                campos['correo'] = true;
            } else {
                error_correo[0].classList.add("aviso-email-error")
                iconoAlerta[0].classList.add("icono-alerta-error")
                campos['correo'] = false;
            }

        break;

        case "nombre":
            if(e.target.value === ''){
                iconoAlerta_name[0].classList.remove("icono-alerta-name-error")
                error_nombre[0].classList.remove("aviso-nombre-error")
                campos['nombre'] = false;
            } else if (expresiones.nombre.test(e.target.value)){
                iconoAlerta_name[0].classList.remove("icono-alerta-name-error")
                error_nombre[0].classList.remove("aviso-nombre-error")
                campos['nombre'] = true;
            }else{
                iconoAlerta_name[0].classList.add("icono-alerta-name-error")
                error_nombre[0].classList.add("aviso-nombre-error")
                campos['nombre'] = false;
            }
        break;

        case "apellido":
            if(e.target.value === ''){
                error_apellido[0].classList.remove("aviso-apellido-error");
                iconoAlerta_lastname[0].classList.remove("icono-alerta-lastname-error");
                campos['apellido'] = false;
            }else if (expresiones.nombre.test(e.target.value)){
                error_apellido[0].classList.remove("aviso-apellido-error");
                iconoAlerta_lastname[0].classList.remove("icono-alerta-lastname-error");
                campos['apellido'] = true;
            }else{
                error_apellido[0].classList.add("aviso-apellido-error");
                iconoAlerta_lastname[0].classList.add("icono-alerta-lastname-error");
                campos['apellido'] = false;
            }
        break;

        case "password":
            if (e.target.value === '') {
                iconoAlerta_psw[0].classList.remove("icono-alerta-psw-error");
                error_psw[0].classList.remove("aviso-psw-error");
                campos['password'] = false;
            } else if (expresiones.password.test(e.target.value)) {
                iconoAlerta_psw[0].classList.remove("icono-alerta-psw-error");
                error_psw[0].classList.remove("aviso-psw-error");
                campos['password'] = true;
            } else {
                iconoAlerta_psw[0].classList.add("icono-alerta-psw-error");
                error_psw[0].classList.add("aviso-psw-error");
                campos['password'] = false;
            }
        break;

        case "verificar_psw":
            validarPassword2(e);
        break;
    }
}

const validarPassword2 = (e) =>{
    const inputPassword1 = document.getElementById('password');
    const inputPassword2 = document.getElementById('password_dos');

    if(e.target.name === "verificar_psw"){
        if(inputPassword1.value !== inputPassword2.value){
            error_verificar[0].classList.add("aviso-verificar-error");
            iconoAlerta_verificar[0].classList.add("icono-alerta-verificar-error");
            campos['password'] = false;
        } else{
            error_verificar[0].classList.remove("aviso-verificar-error");
            iconoAlerta_verificar[0].classList.remove("icono-alerta-verificar-error");
            campos['password'] = true;
        }
    }
}

inputs_v.forEach((input) => {
    input.addEventListener('keyup', validarFormulario);
    input.addEventListener('blur', validarFormulario);
})

function limpiarEstilos() {
    const inputDivs = document.querySelectorAll('.input-div');
    inputDivs.forEach(inputDiv => {
        inputDiv.classList.remove('focus');
    });
}


const aviso_campos = document.getElementsByClassName("alerta-campo");

formulario.addEventListener('submit', (e) => {

    if(campos.nombre && campos.apellido && campos.correo && campos.password){  
        formulario.submit();
    } else{
        e.preventDefault();
        aviso_campos[0].classList.add("alerta-campo_error");  
    }
});
