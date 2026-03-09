<?php
session_start();
include("../database/conexion.php");

$tipo_residuo = $_POST['tipo_residuo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$comunidad = $_POST['comunidad'] ?? ''; // comunidad o barrio
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$fecha_hoy = date("Y-m-d");

// Determinar ID del usuario
if (isset($_SESSION['usuario_id'])) {
    $id_usuario = $_SESSION['usuario_id'];
} else {
    $sql_usuario =  "SELECT id FROM usuarios WHERE correo = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("s", $email);
    $stmt_usuario->execute();
    $result = $stmt_usuario->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $id_usuario = $usuario['id'];
    } else if ($email === '') {
        $id_usuario = 2; // Usuario anónimo
    } else {
        $sql_insert_usuario = "INSERT INTO usuarios (nombre, correo, telefono, comunidad, contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert_usuario);
        $contrasena_generica = password_hash("123456", PASSWORD_DEFAULT);
        $stmt_insert->bind_param("sssss", $nombre, $email, $telefono, $comunidad, $contrasena_generica);
        $stmt_insert->execute();
        $id_usuario = $stmt_insert->insert_id;
    }
}

// Insertar la solicitud de recolección
$sql_recoleccion = "INSERT INTO recolecciones (id_usuario, tipo_residuo, estado, fecha_solicitada)
                    VALUES (?, ?, 'pendiente', ?)";
$stmt_recoleccion = $conexion->prepare($sql_recoleccion);
$stmt_recoleccion->bind_param("iss", $id_usuario, $tipo_residuo, $fecha_hoy);
$stmt_recoleccion->execute();

$conexion->close();

echo "<script>alert('Solicitud de recolección enviada con éxito'); window.history.back();</script>";
?>
