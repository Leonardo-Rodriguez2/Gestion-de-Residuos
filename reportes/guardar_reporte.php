<?php
session_start();
include("../database/conexion.php");


$barrio = $_POST['barrio'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$descripcion = $_POST['descripcion'];


$sql_usuario =  "SELECT id FROM usuarios WHERE correo = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $email);
$stmt_usuario->execute();
$result = $stmt_usuario->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $id_usuario = $usuario['id'];
} else {
    $sql_insert_usuario = "INSERT INTO usuarios (nombre, correo, telefono, comunidad, contrasena) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert_usuario);
    $contrasena_generica = password_hash("123456", PASSWORD_DEFAULT);
    $stmt_insert->bind_param("sssss", $nombre, $email, $telefono, $barrio, $contrasena_generica);
    $stmt_insert->execute();
    $id_usuario = $stmt_insert->insert_id;
}

session_start();
$id_usuario = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 2;
$sql_reporte = "INSERT INTO reportes (id_usuario, descripcion, ubicacion) VALUES (?, ?, ?)";
$stmt_reporte = $conexion->prepare($sql_reporte);
$stmt_reporte->bind_param("iss", $id_usuario, $descripcion, $barrio);
$stmt_reporte->execute();

$conexion->close();


echo "<script>alert('Reporte enviado con éxito'); window.history.back();</script>";
?>
