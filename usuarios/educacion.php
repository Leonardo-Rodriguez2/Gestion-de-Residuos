<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educación Ambiental</title>
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

</head>

<body>
    <header>
        <h1>Educación Ambiental</h1>
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
                <li><a href="../mapa/mapa.php">Mapa</a></li>
                <li><a href="../reportes/recoleccion.php">Recolección</a></li>
                <li><a >Educación</a></li>
                <li><a href="../usuarios/ranking.php">Ranking</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="educacion-ambiental">
            <h2>Guía de Reciclaje</h2>
            <p>El reciclaje es una acción fundamental para reducir la contaminación y conservar los recursos naturales. Aquí te ofrecemos una guía sencilla para reciclar de manera efectiva:</p>
            
            <h3>1. Clasificación de Residuos</h3>
            <p>Para facilitar el reciclaje, es importante separar los residuos correctamente en las siguientes categorías:</p>
            <div class="categorias-residuos">
            <div class="tarjeta" onclick="abrirModal('modalPapel')">
                <img src="../imagenes/papel-carton.png" alt="Papel y Cartón">
                <p><strong>Papel y Cartón</strong><br>Revistas, periódicos, cajas, folletos, cartón limpio.</p>
            </div>
            <div class="tarjeta" onclick="abrirModal('modalVidrio')">
                <img src="../imagenes/vidrio.png" alt="Vidrio">
                <p><strong>Vidrio</strong><br>Botellas, fracos y envaces. Lávalos antes de depositarlos en el contenedor</p>
            </div>
            <div class="tarjeta" onclick="abrirModal('modalPlasticos')">
                <img src="../imagenes/plastico.png" alt="Plásticos">
                <p><strong>Plásticos</strong><br>Botellas, bolsas y envaces de productos. Separa según su tipo (PET, HDPE, etc.).</p>
            </div>
            <div class="tarjeta" onclick="abrirModal('modalMetales')">
                <img src="../imagenes/metales.png" alt="Metales">
                <p><strong>Metales</strong><br>Latas de aluminio y acero. Aplástalas para optimizar el especio.</p>
            </div>
            <div class="tarjeta" onclick="abrirModal('modalOrganicos')">
                <img src="../imagenes/organicos.png" alt="Orgánicos">
                <p><strong>Orgánicos</strong><br>Restos de comida y jardinería, ideales para compostaje.</p>
            </div>
            <div class="tarjeta" onclick="abrirModal('modalTecnologicos')">
                <img src="../imagenes/tec.png" alt="Técnologicos">
                <p><strong>Técnologicos</strong><br>Elementos, objetos, o conceptos relacionados con la tecnología.</p>
            </div>

            </div>
        </section>
        
        <section class="videos-educativos">
            <h2>Videos Educativos</h2>
            <p>Aprende más sobre reciclaje con estos videos:</p>
            <div class="video-container">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/lnI0Py1dPkg?si=p_dF8Cs7Oj6DzLJ1" frameborder="0" allowfullscreen></iframe>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/LoWRxBdDJmE?si=Ho1-vv6Lh5M8L8Pv" frameborder="0" allowfullscreen></iframe>
            </div>
        </section>
    </main>

 <!-- MODAL: papel y carton -->
<div id="modalPapel" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalPapel')">&times;</span>
        <h2>Papel y Cartón</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/fgYTDxMDQgE?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

 <!-- MODAL: vidrio -->
<div id="modalVidrio" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalVidrio')">&times;</span>
        <h2>Vidrio</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/4jETI2NcdrI?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- MODAL: Plásticos -->
<div id="modalPlasticos" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalPlasticos')">&times;</span>
        <h2>Plásticos</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/r71Oe6K_MOY?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- MODAL: Metales -->
<div id="modalMetales" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalMetales')">&times;</span>
        <h2>Metales</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/gvA68oTxLgM?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- MODAL: Orgánicos -->
<div id="modalOrganicos" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalOrganicos')">&times;</span>
        <h2>Orgánicos</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/6j8oHR4qzhM?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- MODAL: Tecnológicos -->
<div id="modalTecnologicos" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal('modalTecnologicos')">&times;</span>
        <h2>Tecnológicos</h2>
        <div class="video-responsive">
            <iframe src="https://www.youtube.com/embed/OBDLx_itTwo?enablejsapi=1&rel=0&modestbranding=1&controls=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>



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

 <script>
 function abrirModal(id) {
     document.getElementById(id).style.display = "flex";
 }

function cerrarModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = "none";

    const iframe = modal.querySelector("iframe");
    if (iframe) {
        const src = iframe.src;
        iframe.src = "";
        iframe.src = src;
    }
}

// Cierra el modal al hacer clic fuera del contenido
window.addEventListener("click", function(e) {
    const modales = document.querySelectorAll(".modal");
    modales.forEach(modal => {
        if (e.target === modal) {
            modal.style.display = "none";

            const iframe = modal.querySelector("iframe");
            if (iframe) {
                const src = iframe.src;
                iframe.src = "";
                iframe.src = src;
            }
        }
    });
});

 </script>

</body>
</html>
