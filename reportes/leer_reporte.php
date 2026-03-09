<?php
include("../database/conexion.php");

function obtenerReportesDelUsuario($usuario_id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT id, fecha, ubicacion, descripcion, foto FROM reportes WHERE id_usuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
