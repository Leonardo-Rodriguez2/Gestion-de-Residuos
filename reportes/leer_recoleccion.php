<?php
include("../database/conexion.php");

function obtenerRecoleccionesDelUsuario($usuario_id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT id, tipo_residuo, direccion, estado, fecha_solicitada, fecha_confirmada FROM recolecciones WHERE id_usuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
