<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Residuos</title>
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
        <h1>Gestión de Residuos</h1>
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
                        <li><a href="../usuarios/educacion.php">Educación</a></li>
                        <li><a >Ranking</a></li>
                    </ul>
                </nav>
            </header>

    <div class="ranking-section">
        <h2>📊 ¿Cómo funciona el Ranking de Residuos?</h2>
        <p>El ranking clasifica a los participantes según sus aportes a la gestión de residuos a nivel nacional. Se otorgan puntos en las siguientes categorías:</p>
        
        <div class="ranking-categories">
            <div class="category">
                <img src="../imagenes/optimizacion.png" alt="Optimización de residuos">
                <h3>Optimización de residuos</h3>
                <p><strong>Optimización de residuos:</strong> Se premia la reducción, reutilización y reciclaje de materiales.</p>
            </div>
            
            <div class="category">
                <img src="../imagenes/aportaciones.png" alt="Aportaciones">
                <h3>Aportaciones</h3>
                <p><strong>Aportaciones:</strong> Se otorgan puntos por iniciativas y donaciones para la gestión de residuos.</p>
            </div>
            
            <div class="category">
                <img src="../imagenes/educacion.png" alt="Educación ambiental">
                <h3>Educación ambiental</h3>
                <p><strong>Educación ambiental:</strong> Participación en campañas y concientización sobre residuos.</p>
            </div>
            
            <div class="category">
                <img src="../imagenes/reportes.png" alt="Reportes ciudadanos">
                <h3>Reportes ciudadanos</h3>
                <p><strong>Reportes ciudadanos:</strong> Se contabilizan los reportes sobre residuos en zonas públicas.</p>
            </div>
        </div>
    </div>
    
    <h3>🏆 Top Organizaciones en el Ranking</h3>
<table class="ranking-table">
    <tr>
        <th>Posición</th>
        <th>Nombre</th>
        <th>Puntos</th>
        <th>Categoría</th>
    </tr>
    <tr>
        <td>🥇 1</td>
        <td>EcoVerde CR</td>
        <td>950</td>
        <td>Optimización</td>
    </tr>
    <tr>
        <td>🥈 2</td>
        <td>ReciclaYa</td>
        <td>870</td>
        <td>Aportaciones</td>
    </tr>
    <tr>
        <td>🥉 3</td>
        <td>GreenFuture</td>
        <td>820</td>
        <td>Educación</td>
    </tr>
</table>
<h3>🌍 Acciones que Impactan el Ranking</h3>
<div class="imagenes-ranking">
    <div class="imagen">
        <img src="../imagenes/reciclaje.jpg" alt="Reciclaje de materiales">
        <p>🔄 Reciclaje y reutilización</p>
    </div>
    <div class="imagen">
        <img src="../imagenes/Educacion_ambiental_niños.jpg" alt="Educación ambiental">
        <p>📢 Campañas de concienciación</p>
    </div>
    <div class="imagen">
        <img src="../imagenes/voluntariado.jpg" alt="Voluntariado ambiental">
        <p>💚 Voluntariado ecológico</p>
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
</body>

</html>