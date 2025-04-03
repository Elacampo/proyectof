//Ejecutando funciones
document.getElementById("lupa").addEventListener("click", mostrar_Buscador);

document.getElementById("cover").addEventListener("click",ocultar_Buscador);

//Declarando variables
barra_busqueda = document.getElementById("cnt_barra");
cover_cnt = document.getElementById("cover");
inputBusqueda = document.getElementById("inputSearch");

//Funcion para mostrar el buscador
function mostrar_Buscador(){

    barra_busqueda.style.top="75px";
    cover_cnt.style.display = "block";
    inputBusqueda.focus();
}


//Funcion para ocultar el buscador
function ocultar_Buscador(){
    barra_busqueda.style.top= "-100px";
    cover_cnt.style.display = "none";
    inputBusqueda.value = "";
}
