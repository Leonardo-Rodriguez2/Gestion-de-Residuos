<div class="container is-fluid mb-6">
	<h1 class="title">Productos Proveedores</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar relación producto-proveedor</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
	
		include "./app/views/inc/btn_back.php";

		$id=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Unico","producto_proveedor","producto_proveedor_id",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();

			# Obtener datos del producto
			$producto=$insLogin->seleccionarDatos("Unico","producto","producto_id",$datos['producto_id']);
			$producto=$producto->fetch();

			# Obtener datos del proveedor
			$proveedor=$insLogin->seleccionarDatos("Unico","proveedor","proveedor_id",$datos['proveedor_id']);
			$proveedor=$proveedor->fetch();
	?>
	
	<h2 class="title has-text-centered"><?php echo $producto['producto_nombre']." - ".$proveedor['proveedor_nombre']; ?></h2>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productSupplierAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_productSupplier" value="actualizar">
		<input type="hidden" name="producto_proveedor_id" value="<?php echo $datos['producto_proveedor_id']; ?>">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Producto <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				  	<div class="select">
					  	<select name="producto_id">
	                        <?php
	                        	$datos_productos=$insLogin->seleccionarDatos("Normal","producto","*",0);
	                        	while($campos_producto=$datos_productos->fetch()){
	                        		if($campos_producto['producto_id']==$datos['producto_id']){
	                        			echo '<option value="'.$campos_producto['producto_id'].'" selected="" >'.$campos_producto['producto_nombre'].'</option>';
	                        		}else{
	                                	echo '<option value="'.$campos_producto['producto_id'].'">'.$campos_producto['producto_nombre'].'</option>';
	                        		}
	                        	}
	                        ?>
					  	</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Proveedor <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				  	<div class="select">
					  	<select name="proveedor_id">
	                        <?php
	                        	$datos_proveedores=$insLogin->seleccionarDatos("Normal","proveedor","*",0);
	                        	while($campos_proveedor=$datos_proveedores->fetch()){
	                        		if($campos_proveedor['proveedor_id']==$datos['proveedor_id']){
	                        			echo '<option value="'.$campos_proveedor['proveedor_id'].'" selected="" >'.$campos_proveedor['proveedor_nombre'].'</option>';
	                        		}else{
	                                	echo '<option value="'.$campos_proveedor['proveedor_id'].'">'.$campos_proveedor['proveedor_nombre'].'</option>';
	                        		}
	                        	}
	                        ?>
					  	</select>
					</div>
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Precio <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="producto_proveedor_precio" value="<?php echo $datos['producto_proveedor_precio']; ?>" pattern="[0-9.]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Unidad de medida</label>
				  	<input class="input" type="text" name="producto_proveedor_unidad_medida" value="<?php echo $datos['producto_proveedor_unidad_medida']; ?>" maxlength="30" >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
		</p>
		<p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
	</form>
	<?php
		}else{
			include "./app/views/inc/error_alert.php";
		}
	?>
</div>
