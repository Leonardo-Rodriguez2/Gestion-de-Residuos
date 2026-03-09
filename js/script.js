// Esperar a que el DOM esté completamente cargado antes de ejecutar el script
// Manejo de formularios y eventos

document.addEventListener("DOMContentLoaded", function () {
    // Manejo del formulario de reportes
    const formReporte = document.getElementById('formReporte');
    if (formReporte) {
        formReporte.addEventListener('submit', function (event) {
            event.preventDefault();
            const descripcion = document.getElementById('descripcion').value;
            console.log(`Reporte enviado: ${descripcion}`);
        });
    } else {
        console.warn("El formulario de reporte no fue encontrado en el DOM.");
    }

    // Manejo del formulario de recolección de residuos
    const formRecoleccion = document.getElementById("formRecoleccion");
    if (formRecoleccion) {
        formRecoleccion.addEventListener("submit", function (event) {
            event.preventDefault();
            const tipoResiduo = document.getElementById("tipo-residuo").value;
            const direccion = document.getElementById("direccion").value;
            alert(`Solicitud de recolección enviada para: ${tipoResiduo} en ${direccion}`);
        });
    } else {
        console.warn("El formulario de recolección no fue encontrado en el DOM.");
    }

    // Manejo de botones de educación ambiental
    const botonesEducacion = document.querySelectorAll(".btn-educacion");
    if (botonesEducacion.length > 0) {
        botonesEducacion.forEach(boton => {
            boton.addEventListener("click", function () {
                const tema = this.dataset.tema;
                alert(`Mostrando información sobre: ${tema}`);
            });
        });
    } else {
        console.warn("No se encontraron botones de educación en el DOM.");
    }

    // Inicializar el mapa en Costa Rica
    document.addEventListener("DOMContentLoaded", function () {
        var map = L.map('map').setView([9.7489, -83.7534], 8); // Mapa centrado en Costa Rica
    
        // Agregar capa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    
        // Cargar reportes desde PHP
        fetch('obtener_reportes.php')
            .then(response => response.json())
            .then(data => {
                console.log("Datos recibidos:", data);  // Depuración: verifica si llegan datos
    
                data.forEach(reporte => {
                    if (reporte["Coordenadas"]) {
                        let coordenadas = convertirDMSaDecimal(reporte["Coordenadas"]);
                        if (coordenadas) {
                            L.marker([coordenadas.lat, coordenadas.lng])
                                .addTo(map)
                                .bindPopup(`<strong>${reporte["Descripción"]}</strong><br>${reporte["Provincia"]}, ${reporte["Cantón"]}`);
                        }
                    }
                });
            })
            .catch(error => console.error("Error al cargar reportes:", error));
    });
    
    
    // Función para convertir coordenadas DMS (grados, minutos, segundos) a decimales
    function convertirDMSaDecimal(dms) {
        const regex = /(\d+)°(\d+)'(\d+)"([NS])\s+(\d+)°(\d+)'(\d+)"([EW])/;
        let match = dms.match(regex);
    
        if (match) {
            let lat = parseFloat(match[1]) + parseFloat(match[2]) / 60 + parseFloat(match[3]) / 3600;
            let lng = parseFloat(match[5]) + parseFloat(match[6]) / 60 + parseFloat(match[7]) / 3600;
    
            if (match[4] === "S") lat *= -1;
            if (match[8] === "W") lng *= -1;
    
            return { lat, lng };
        }
    
        return null;
    }
});
