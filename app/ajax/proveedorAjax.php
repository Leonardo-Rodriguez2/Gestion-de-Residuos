<?php

    /*--------- Rutas de inclusión ajustadas según tu ejemplo ---------*/
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php"; // Incluye el inicio de sesión
    require_once "../../autoload.php";            // El Autoloader está dos niveles arriba
    
    use app\controllers\providerController;

    /*--------- Comprobando que se reciba el módulo ---------*/
    if(isset($_POST['modulo_proveedor'])){

        /*--------- Instancia del controlador de proveedor ---------*/
        $insProveedor = new providerController();

        /*--------- Registrar proveedor ---------*/
        if($_POST['modulo_proveedor']=="registrar"){
            echo $insProveedor->registrarProveedorControlador();
        }

        /*--------- Actualizar proveedor ---------*/
        if($_POST['modulo_proveedor']=="actualizar"){
            echo $insProveedor->actualizarProveedorControlador();
        }

        /*--------- Eliminar proveedor ---------*/
        if($_POST['modulo_proveedor']=="eliminar"){
            echo $insProveedor->eliminarProveedorControlador();
        }
        // Nota: A diferencia del controlador de productos, el controlador de proveedores 
        // no maneja fotos directamente. Si necesitas esas funciones, se deben agregar aquí.

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }