<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo</title>
    <link rel="stylesheet" href="../css/styles.css">

     <style>
              .auth-buttons {
                  position: absolute;
                  top: 20px;
                  right: 20px;
              }
              .auth-buttons button {
                  margin-left: 10px;
                  padding: 10px;
                  background: #fff;
                  color: #2c3e50;
                  font-weight: bold;
                  border: none;
                  border-radius: 5px;
                  cursor: pointer;
              }
              .modal {
                  display: none;
                  position: fixed;
                  z-index: 9999;
                  left: 0;
                  top: 0;
                  width: 100%;
                  height: 100%;
                  background-color: rgba(0, 0, 0, 0.5);
              }
              .modal-content {
                  background-color: #fff;
                  margin: 10% auto;
                  padding: 20px;
                  border-radius: 10px;
                  width: 300px;
                  position: relative;
              }
              .modal-content input, .modal-content button {
                  width: 100%;
                  padding: 10px;
                  margin: 5px 0;
              }
              .close {
                  position: absolute;
                  right: 10px;
                  top: 5px;
                  cursor: pointer;
                  font-size: 20px;
              }
          </style>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; margin: 20px 0; }
        .filtro-container { margin: 20px 0; }
    </style>
</head>
<body>

<header>
    <h1>Mapa Interactivo e Informativo</h1>
    <div class="auth-buttons">
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="../usuarios/perfil_usuario.php"><button>Mi perfil</button></a>
        <?php else: ?>
            <button onclick="mostrarModal('loginModal')">Iniciar sesión</button>
            <button onclick="mostrarModal('registroModal')">Registrarse</button>
        <?php endif; ?>
    </div>
    <nav>
        <ul>
            <li><a href="../index.php">Inicio</a></li>
            <li><a href="../reportes/reportes.php">Reportes</a></li>
            <li><a >Mapa</a></li>
            <li><a href="../reportes/recoleccion.php">Recolección</a></li>
            <li><a href="../usuarios/educacion.php">Educación</a></li>
            <li><a href="../usuarios/ranking.php">Ranking</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="map-container">
        <h2>Ubicación de Centros de Reciclaje, Biodigestores y Eventos</h2>
        <p class="description">
            Este mapa muestra los lugares en tu comunidad donde podés dejar materiales reciclables, encontrar eventos o conocer tecnologías como biodigestores.
        </p>

        <div class="filtro-container">
            <label for="filtro-tipo">Filtrar por tipo:</label>
            <select id="filtro-tipo">
                <option value="todos">Todos</option>
                <option value="reciclaje">Reciclaje</option>
                <option value="biodigestor">Biodigestor</option>
                <option value="evento">Evento</option>
            </select>
        </div>

        <div id="map"></div>
    </section>
</main>

 <!-- MODAL: Registro -->
 <div id="registroModal" class="modal">
     <div class="modal-content">
         <span class="close" onclick="cerrarModales()">&times;</span>
         <h3>Registro</h3>
         <form action="../usuarios/backend_usuario.php" method="POST">
             <input type="hidden" name="accion" value="registro">
             <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
             <select name="comunidad" required>
                 <option value="El Roble">Comunidad El Roble</option>
             </select>
             <input type="email" name="correo" placeholder="Correo electrónico" required>
             <input type="password" name="contrasena" placeholder="Contraseña" required>
             <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
             <button type="submit">Registrarme</button>
         </form>
     </div>
 </div>


 <!-- MODAL: Login -->
 <div id="loginModal" class="modal">
     <div class="modal-content">
         <span class="close" onclick="cerrarModales()">&times;</span>
         <h3>Iniciar sesión</h3>
         <form action="../usuarios/backend_usuario.php" method="POST">
             <input type="hidden" name="accion" value="login">
             <input type="email" name="correo" placeholder="Correo electrónico" required>
             <input type="password" name="contrasena" placeholder="Contraseña" required>
             <button type="submit">Entrar</button>
         </form>
     </div>
 </div>


 <footer>
     <p>&copy; 2025 Gestión de Residuos - Grupo 8 - Ambiente Web Cliente Servidor - Universidad Fidelitas</p>
 </footer>

 <script>
     function mostrarModal(id) {
         cerrarModales();
         document.getElementById(id).style.display = 'block';
     }
     function cerrarModales() {
         document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
     }
     window.onclick = function(e) {
         if (e.target.classList.contains('modal')) cerrarModales();
     }
 </script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([9.9844, -84.7343], 14); // El Roble

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marcadores = [];

    // Íconos personalizados
    const iconos = {
        reciclaje: L.icon({
            iconUrl: 'icons/reciclaje.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        }),
        biodigestor: L.icon({
            iconUrl: 'icons/biodigestor.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        }),
        evento: L.icon({
            iconUrl: 'icons/evento.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        })
    };

    function cargarPuntos(filtro) {
        fetch('puntos_mapa.php')
            .then(res => res.json())
            .then(data => {
                // Limpiar marcadores anteriores
                marcadores.forEach(m => map.removeLayer(m));
                marcadores = [];

                data.forEach(lugar => {
                    if (filtro === 'todos' || lugar.tipo === filtro) {
                        const icono = iconos[lugar.tipo] || null;

                        const marcador = L.marker([lugar.latitud, lugar.longitud], { icon: icono })
                            .bindPopup(`<strong>${lugar.nombre}</strong><br>${lugar.descripcion}`);

                        marcador.addTo(map);
                        marcadores.push(marcador);
                    }
                });
            });
    }

    // Filtro por tipo
    document.getElementById("filtro-tipo").addEventListener("change", e => {
        cargarPuntos(e.target.value);
    });

    // Cargar todos al inicio
    cargarPuntos('todos');

    // Dibujar perímetro desde el archivo GeoJSON
    fetch('el_roble.geojson')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    color: '#228B22',
                    weight: 2,
                    fillColor: '#aaffaa',
                    fillOpacity: 0.2
                }
            }).addTo(map).bindPopup("Perímetro aproximado de El Roble");
        });
</script>

</body>
</html>
