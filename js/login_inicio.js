const inputs = document.querySelectorAll(".input");

function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
	if(this.type == "email"){
		this.setAttribute("placeholder", "Ingrese su correo electronico");
	} else if(this.type == "password"){
		this.setAttribute("placeholder", "Ingrese su contraseña");
	}
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
		this.removeAttribute("placeholder");
	}
}

inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});