<?php

class db {
    public $host = 'localhost';
    public $username = 'root';
    public $password = '';
    public $dbname = 'db_gestionresiduos';
}

$db = new db();

$conexion = new mysqli( $db->host, $db->username, $db->password, $db->dbname);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>