<?php
session_start();

// Depuración: quitar temporalmente la redirección para ver errores
// if (!isset($_SESSION['usuario_id'])) {
//     header("Location: ../index.php");
//     exit();
// }

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "Error: No has iniciado sesión. Redirigiendo...";
    header("Location: ../index.php");
    exit();
}

include("../database/conexion.php");
$id_usuario = $_SESSION['usuario_id'];

echo "<p>Debug: ID de sesión: " . $id_usuario . "</p>"; // Quitar después de probar

include("../reportes/leer_reporte.php");
$reportes = obtenerReportesDelUsuario($_SESSION['usuario_id']);
include("../reportes/leer_recoleccion.php");
$recolecciones = obtenerRecoleccionesDelUsuario($_SESSION['usuario_id']);

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    echo "Error: Usuario no encontrado en la BD.";
    exit();
}

echo "<p>Debug: Usuario encontrado: " . $usuario['nombre'] . "</p>"; // Quitar después de probar
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .header a {
            background-color: #27ae60;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #d0ead5;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .sidebar h3 {
            margin-top: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin: 15px 0;
        }
        .sidebar li a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
        }
        .content {
            flex: 1;
            padding: 30px;
            background: white;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            padding: 10px 20px;
            background: #0277bd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .foto-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
<div class="header">
    <h1>Mi Perfil</h1>
    <a href="../index.php">🏠 Home</a>
</div>

<div class="container">
    <div class="sidebar">
        <h3>Menú</h3>
        <ul>
            <li><a href="#info">Información básica</a></li>
            <li><a href="#reportes">Mis reportes</a></li>
            <li><a href="#solicitudes">Mis solicitudes</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>

    <div class="content">
<section id="info" class="section">
    <h2>Información Básica</h2>
    <form action="guardar_usuarios.php" method="POST" enctype="multipart/form-data">
        <?php if (!empty($usuario['foto_perfil'])): ?>
            <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" class="foto-preview" alt="Foto de perfil">
        <?php endif; ?>

        <div class="form-group">
            <label>Foto de perfil</label>
            <input type="file" name="foto_perfil">
        </div>

        <div class="form-group">
            <label>Nombre de usuario</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" readonly>
        </div>

        <div class="form-group">
            <label>Número de teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <textarea name="direccion"><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Comunidad</label>
            <select name="comunidad">
                <option value="El Roble" <?= ($usuario['comunidad'] ?? '') === 'El Roble' ? 'selected' : '' ?>>El Roble</option>
            </select>
        </div>

        <div class="form-group">
            <label>Número de casa</label>
            <input type="text" name="numero_casa" value="<?= htmlspecialchars($usuario['numero_casa'] ?? '') ?>">
        </div>

        <button class="btn" type="submit">Guardar cambios</button>
    </form>
</section>


<section id="reportes" class="section">
    <div id="mis-reportes" class="tab-content">
        <h3>Mis reportes</h3>

        <?php if ($reportes->num_rows > 0): ?>
            <table class="tabla-reportes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Ubicación</th>
                        <th>Descripción</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $reportes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                            <td><?= htmlspecialchars($row['ubicacion']) ?></td>
                            <td><?= htmlspecialchars($row['descripcion']) ?></td>
                            <td>
                                <?php if (!empty($row['foto'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has realizado reportes aún.</p>
        <?php endif; ?>

        <a href="../reportes/reportes.php" class="btn btn-reporte">Crear nuevo reporte</a>
    </div>
</section>

<section id="solicitudes" class="section">
    <h2>Mis Solicitudes</h2>

    <?php if ($recolecciones->num_rows > 0): ?>
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo de residuo</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Fecha solicitada</th>
                    <th>Fecha confirmada</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $recolecciones->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['tipo_residuo']) ?></td>
                        <td><?= htmlspecialchars($row['direccion']) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_solicitada']) ?></td>
                        <td><?= $row['fecha_confirmada'] ? htmlspecialchars($row['fecha_confirmada']) : '—' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No has realizado solicitudes de recolección aún.</p>
    <?php endif; ?>

    <a href="../reportes/recoleccion.php" class="btn btn-reporte">Crear nueva solicitud</a>
</section>


    </div>
</div>
</body>

</html>
