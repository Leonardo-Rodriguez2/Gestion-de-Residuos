<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\productSupplierController;

	if(isset($_POST['modulo_productSupplier'])){

		$insProductSupplier = new productSupplierController();

		if($_POST['modulo_productSupplier']=="registrar"){
			echo $insProductSupplier->registrarProductoProveedorControlador();
		}

		if($_POST['modulo_productSupplier']=="actualizar"){
			echo $insProductSupplier->actualizarProductoProveedorControlador();
		}

		if($_POST['modulo_productSupplier']=="eliminar"){
			echo $insProductSupplier->eliminarProductoProveedorControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}
