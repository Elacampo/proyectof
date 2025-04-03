const  body = document.querySelector("body"),
        sidebar = body.querySelector(".sidebar"),
        toggle = body.querySelector(".toggle"),
        modeSwitch= body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");
        toggle.addEventListener("click", () =>{
            sidebar.classList.toggle("close");
        });



document.addEventListener('DOMContentLoaded', function () {
    const inventarioLink = document.querySelector('.nav-link a[href="#inventario.php"]');
    const reporteLink = document.querySelector('.nav-link a[href="#reporte.php"]');

    const menuInside = document.querySelector('.menu-links--inside');
    const reportMenuInside = document.querySelector('.menu-links--inside--report');

    const sidebar = document.querySelector('.sidebar');

    const iconArrow = document.getElementById('icon-arrow');
    const iconArrow_report = document.getElementById('icon-arrow-report');

    inventarioLink.addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del enlace
        
        if (sidebar.classList.contains('close')) {
            window.location.href = 'inventario.php';
        } else {
            // Toggle para mostrar u ocultar el menú inside
            iconArrow.classList.toggle('rotate');
            menuInside.classList.toggle('active');
        }
    });

    reporteLink.addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del enlace

            if(sidebar.classList.contains('close')){
                window.location.href = 'reportes.php'

            }else{
                iconArrow_report.classList.toggle('rotate');
                reportMenuInside.classList.toggle('active');
            } 
    });
   
});


