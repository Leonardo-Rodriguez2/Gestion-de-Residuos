<?php
session_start();

// Conexión a la base de datos

require_once '../database/conexion.php';

// Verificar tipo de acción
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'login') {
        // LOGIN
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario'] = $usuario['nombre'];
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['comunidad'] = $usuario['comunidad'];
                header("Location: ../index.php");
                exit();
            } else {
                echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Correo no registrado.'); window.history.back();</script>";
            exit();
        }

    } elseif ($_POST['accion'] === 'registro') {
        // REGISTRO
        $nombre = $_POST['nombre_usuario'];
        $correo = $_POST['correo'];
        $comunidad = $_POST['comunidad'];
        $contrasena = $_POST['contrasena'];
        $confirmar = $_POST['confirmar'];

        if ($contrasena !== $confirmar) {
            echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
            exit();
        }

        $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

        // Verificar si ya existe el correo
        $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $verificar->bind_param("s", $correo);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            echo "<script>alert('Este correo ya está registrado.'); window.history.back();</script>";
            exit();
        } else {
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena, comunidad) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hashed, $comunidad);

            if ($stmt->execute()) {
                $_SESSION['usuario'] = $nombre;
                $_SESSION['usuario_id'] = $stmt->insert_id;
                $_SESSION['comunidad'] = $comunidad;
                header("Location: ../index.php");

                exit();
            } else {
                echo "<script>alert('Error al registrar usuario.'); window.history.back();</script>";
                exit();
            }
        }

    } else {
        echo "<script>alert('Acción no reconocida.'); window.history.back();</script>";
        exit();
    }

} else {
    echo "<script>alert('Solicitud no válida.'); window.history.back();</script>";
    exit();
}


