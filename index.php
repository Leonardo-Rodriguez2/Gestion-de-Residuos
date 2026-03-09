<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Residuos</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="imagenes/icono.png">
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
            <a href="usuarios/perfil_usuario.php"><button>Mi perfil</button></a>
        <?php else: ?>
            <button onclick="mostrarModal('loginModal')">Iniciar sesión</button>
            <button onclick="mostrarModal('registroModal')">Registrarse</button>
        <?php endif; ?>
    </div>
    <nav>
        <ul>
            <li><a >Inicio</a></li>
            <li><a href="reportes/reportes.php">Reportes</a></li>
            <li><a href="mapa/mapa.php">Mapa</a></li>
            <li><a href="reportes/recoleccion.php">Recolección</a></li>
            <li><a href="usuarios/educacion.php">Educación</a></li>
            <li><a href="usuarios/ranking.php">Ranking</a></li>
        </ul>
    </nav>
</header>

<main>
    <!-- Banner Principal -->
    <section id="banner">
        <img src="imagenes/banner-reciclaje.jpg" alt="Reciclaje y Medio Ambiente">
    </section>

    <!-- Sección de Bienvenida Mejorada -->
    <section id="bienvenida">
        <h2>Bienvenido a la plataforma de Gestión de Residuos</h2>
        <p>Facilitamos el reciclaje, la educación ambiental y la recolección personalizada para proteger nuestro planeta.</p>
        <p>Con nuestra plataforma, podrás:</p>
        <ul>
            <li>♻️ Acceder a programas de reciclaje adaptados a tu comunidad.</li>
            <li>📚 Recibir información sobre cómo reducir tu huella de carbono.</li>
            <li>🌍 Participar en campañas y eventos ecológicos.</li>
            <li>🏭 Conectar con empresas que optimizan la gestión de residuos.</li>
        </ul>
        <a href="#" class="btn" onclick="mostrarModal('registroModal')">Únete a nuestra comunidad</a>
    </section>

    <!-- Sección de Importancia de la Gestión de Residuos Mejorada -->
    <section id="importancia-gestion">
        <h2>La Importancia de la Gestión de Residuos</h2>
        <p>La gestión adecuada de residuos es fundamental para proteger el medio ambiente y garantizar la
            sostenibilidad de nuestros recursos naturales...</p>
        <div class="importancia-contenido">
            <div class="importancia-item">
                <img src="imagenes/reciclaje.png" alt="Reciclaje">
                <p>El reciclaje reduce la contaminación y el consumo de recursos.</p>
            </div>
            <div class="importancia-item">
                <img src="imagenes/contaminacion.png" alt="Contaminación">
                <p>La mala gestión de residuos puede provocar contaminación del agua y el aire.</p>
            </div>
            <div class="importancia-item">
                <img src="imagenes/sostenibilidad.png" alt="Sostenibilidad">
                <p>Implementar sistemas de recolección adecuados mejora la sostenibilidad ambiental.</p>
            </div>
        </div>
    </section>

    <!-- Video Informativo -->
    <section id="video-info">
        <h2>¿Por qué es importante gestionar los residuos?</h2>
        <p>Para ver el video completo, haz clic en el enlace a continuación:</p>
        <a href="https://www.youtube.com/watch?v=VAOh0FRYlqI" target="_blank" style="color: #007bff; text-decoration: none; font-weight: bold;">Ver video en YouTube</a>
    </section>

    <!-- Campañas Activas -->
    <section id="campañas">
        <h2>Campañas Activas</h2>
        <div class="campaña">
            <img src="imagenes/campana1.jpg" alt="Campaña de Reciclaje">
            <p><strong>Recicla Hoy</strong> - Ayuda a reducir la contaminación participando en nuestra campaña de reciclaje.</p>
            <a href="#">Más información</a>
        </div>
        <div class="campaña">
            <img src="imagenes/campana2.jpg" alt="Reducción de Plásticos">
            <p><strong>Menos Plástico, Más Vida</strong> - Únete a la iniciativa para reducir el uso de plásticos.</p>
            <a href="#">Únete ahora</a>
        </div>
    </section>

    <!-- Testimonios -->
    <section id="testimonios">
        <h2>Lo que dicen nuestros participantes</h2>
        <div class="testimonio">
            <img src="imagenes/juan_perez.jpg" alt="Foto de Juan Pérez">
            <blockquote>
                "Gracias a esta plataforma, he aprendido a reducir mis residuos y a reciclar de manera efectiva."
                <br> - Juan Pérez
            </blockquote>
        </div>
        <div class="testimonio">
            <img src="imagenes/ana_gomez.jpg" alt="Foto de Ana Gómez">
            <blockquote>
                "Una excelente iniciativa para cuidar nuestro planeta. ¡Súmate al cambio!"
                <br> - Ana Gómez
            </blockquote>
        </div>
    </section>

    <!-- Infografía Interactiva -->
    <section id="infografia">
        <h2>¿Cómo funciona la gestión de residuos?</h2>
        <img src="imagenes/infografia-residuos.jpg" alt="Proceso de reciclaje y gestión de residuos">
    </section>

    <!-- Botón de Acción -->
    <section id="cta">
        <h2>¿Quieres ser parte del cambio?</h2>
        <p>Únete a nuestras campañas y ayúdanos a mejorar el medio ambiente.</p>
        <a href="#" class="btn" onclick="mostrarModal('registroModal')">Participar</a>
    </section>
</main>

<!-- MODAL: Registro -->
<div id="registroModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModales()">&times;</span>
        <h3>Registro</h3>
        <form action="usuarios/backend_usuario.php" method="POST">
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
        <form action="usuarios/backend_usuario.php" method="POST">
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
    // Pasar datos de sesión a JavaScript
    var usuarioLogueado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;
    var usuarioId = <?php echo isset($_SESSION['usuario_id']) ? json_encode($_SESSION['usuario_id']) : 'null'; ?>;

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
    // Depuración: mostrar si el usuario está logueado
    console.log('Usuario logueado:', usuarioLogueado);
    console.log('ID de usuario:', usuarioId);
</script>
</body>

</html>
