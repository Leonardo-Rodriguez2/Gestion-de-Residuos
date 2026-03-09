<?php
include("../database/conexion.php");

$result = $conexion->query("SELECT * FROM lugares_mapa");

$lugares = [];
while ($fila = $result->fetch_assoc()) {
    $lugares[] = $fila;
}

echo json_encode($lugares);
?>