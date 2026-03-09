<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recolección de Residuos</title>
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
    <h1>Solicitar una Recolección de Residuos</h1>
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
            <li><a >Recolección</a></li>
            <li><a href="../usuarios/educacion.php">Educación</a></li>
            <li><a href="../usuarios/ranking.php">Ranking</a></li>
        </ul>
    </nav>
</header>

    <main>
<section id="solicitud-recoleccion">
    <h2>Solicitud de Recolección</h2>

    <form action="guardar_recoleccion.php" method="POST">
        <div class="form-group">
            <label for="comunidad">Comunidad:</label>
            <select id="comunidad" name="comunidad" required>
                <option value="">Seleccione una comunidad</option>
                <option value="El Roble">El Roble</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tipo_residuo">Tipo de residuo:</label>
            <select id="tipo_residuo" name="tipo_residuo" required>
                <option value="Orgánico">Orgánico</option>
                <option value="Reciclable">Reciclable</option>
                <option value="Tecnológico">Tecnológico</option>
                <option value="Peligroso">Peligroso</option>
            </select>
        </div>

        <?php if (!isset($_SESSION['usuario'])): ?>
            <p style="color: red; font-weight: bold; background: white; padding: 10px; border-radius: 5px;">
                ⚠️ Esta solicitud se enviará como <strong>anónima</strong>. ⚠️<br>
                Si desea que quede registrada con su nombre, por favor inicie sesión.
            </p>
        <?php endif; ?>

        <button type="submit" class="submit-btn">Enviar Solicitud</button>
    </form>
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

    <script src="script.js"></script>
</body>
</html>
