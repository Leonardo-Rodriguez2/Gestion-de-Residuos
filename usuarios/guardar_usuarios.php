<?php
session_start();

include("../database/conexion.php");

// Variables del formulario
$id = $_SESSION['usuario_id'];
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$comunidad = isset($_POST['comunidad']) ? $_POST['comunidad'] : '';
$numero_casa = isset($_POST['numero_casa']) ? $_POST['numero_casa'] : '';
$foto_perfil = null;

// Procesar foto si se subió
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === 0) {
    $carpeta_destino = 'imagenes/perfil/';
    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0755, true);
    }

    $nombre_archivo = basename($_FILES['foto_perfil']['name']);
    $ruta_final = $carpeta_destino . time() . '_' . $nombre_archivo;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_final)) {
        $foto_perfil = $ruta_final;
    }
}

// Armar consulta
if ($foto_perfil) {
    $sql = "UPDATE usuarios SET nombre=?, telefono=?, direccion=?, comunidad=?, numero_casa=?, foto_perfil=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $telefono, $direccion, $comunidad, $numero_casa, $foto_perfil, $id);
} else {
    $sql = "UPDATE usuarios SET nombre=?, telefono=?, direccion=?, comunidad=?, numero_casa=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $telefono, $direccion, $comunidad, $numero_casa, $id);
}

if ($stmt->execute()) {
    $_SESSION['usuario'] = $nombre; // actualizar nombre en sesión
    header("Location: perfil_usuario.php");
    exit();
} else {
    echo "Error al guardar los datos.";
}

$conexion->close();
?>

