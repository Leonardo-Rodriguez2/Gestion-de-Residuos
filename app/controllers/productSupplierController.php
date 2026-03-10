<?php

	namespace app\controllers;
	use app\models\mainModel;
	use FPDF;

	class productSupplierController extends mainModel{

		/*----------  Controlador registrar producto proveedor  ----------*/
		public function registrarProductoProveedorControlador(){

			# Almacenando datos#
		    $producto_id=$this->limpiarCadena($_POST['producto_id']);
		    $proveedor_id=$this->limpiarCadena($_POST['proveedor_id']);
		    $precio=$this->limpiarCadena($_POST['producto_proveedor_precio']);
		    $unidad_medida=$this->limpiarCadena($_POST['producto_proveedor_unidad_medida']);

		    # Verificando campos obligatorios #
            if($producto_id=="" || $proveedor_id=="" || $precio==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            # Verificando integridad de los datos #
		    if($this->verificarDatos("[0-9]{1,20}",$producto_id)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRODUCTO ID no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9]{1,10}",$proveedor_id)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PROVEEDOR ID no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9.]{1,25}",$precio)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Verificando producto #
		    $check_producto=$this->ejecutarConsulta("SELECT producto_id FROM producto WHERE producto_id='$producto_id'");
		    if($check_producto->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El producto seleccionado no existe en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Verificando proveedor #
		    $check_proveedor=$this->ejecutarConsulta("SELECT proveedor_id FROM proveedor WHERE proveedor_id='$proveedor_id'");
		    if($check_proveedor->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El proveedor seleccionado no existe en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Comprobando precio #
            $precio=number_format($precio,MONEDA_DECIMALES,'.','');
            if($precio<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO no puede ser menor o igual a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

		    if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\/ -]{1,30}",$unidad_medida)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La UNIDAD DE MEDIDA no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Comprobando si ya existe la relación #
		    $check_relacion=$this->ejecutarConsulta("SELECT producto_proveedor_id FROM producto_proveedor WHERE producto_id='$producto_id' AND proveedor_id='$proveedor_id'");
		    if($check_relacion->rowCount()>=1){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Ya existe una relación entre este producto y proveedor",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    $fecha_registro=date('Y-m-d');

    		$producto_proveedor_datos_reg=[
				[
					"campo_nombre"=>"producto_id",
					"campo_marcador"=>":Producto",
					"campo_valor"=>$producto_id
				],
				[
					"campo_nombre"=>"proveedor_id",
					"campo_marcador"=>":Proveedor",
					"campo_valor"=>$proveedor_id
				],
				[
					"campo_nombre"=>"producto_proveedor_precio",
					"campo_marcador"=>":Precio",
					"campo_valor"=>$precio
				],
				[
					"campo_nombre"=>"producto_proveedor_unidad_medida",
					"campo_marcador"=>":UnidadMedida",
					"campo_valor"=>$unidad_medida
				],
				[
					"campo_nombre"=>"pp_fecha_registro",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha_registro
				]
			];

			$registrar_relacion=$this->guardarDatos("producto_proveedor",$producto_proveedor_datos_reg);

			if($registrar_relacion->rowCount()==1){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Relación registrada",
					"texto"=>"La relación producto-proveedor se registro con exito",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la relación, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}






		/*----------  Controlador actualizar producto proveedor  ----------*/
		public function actualizarProductoProveedorControlador(){

			$id=$this->limpiarCadena($_POST['producto_proveedor_id']);

			# Verificando relación #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto_proveedor WHERE producto_proveedor_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado la relación en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Almacenando datos#
		    $producto_id=$this->limpiarCadena($_POST['producto_id']);
		    $proveedor_id=$this->limpiarCadena($_POST['proveedor_id']);
		    $precio=$this->limpiarCadena($_POST['producto_proveedor_precio']);
		    $unidad_medida=$this->limpiarCadena($_POST['producto_proveedor_unidad_medida']);

		    # Verificando campos obligatorios #
            if($producto_id=="" || $proveedor_id=="" || $precio==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            # Verificando integridad de los datos #
		    if($this->verificarDatos("[0-9]{1,20}",$producto_id)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRODUCTO ID no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9]{1,10}",$proveedor_id)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PROVEEDOR ID no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9.]{1,25}",$precio)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Verificando producto #
			if($datos['producto_id']!=$producto_id){
			    $check_producto=$this->ejecutarConsulta("SELECT producto_id FROM producto WHERE producto_id='$producto_id'");
			    if($check_producto->rowCount()<=0){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El producto seleccionado no existe en el sistema",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
			}

		    # Verificando proveedor #
			if($datos['proveedor_id']!=$proveedor_id){
			    $check_proveedor=$this->ejecutarConsulta("SELECT proveedor_id FROM proveedor WHERE proveedor_id='$proveedor_id'");
			    if($check_proveedor->rowCount()<=0){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El proveedor seleccionado no existe en el sistema",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
			}

		    # Comprobando precio #
            $precio=number_format($precio,MONEDA_DECIMALES,'.','');
            if($precio<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO no puede ser menor o igual a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

		    # Comprobando si ya existe la relación (solo si cambió) #
			if($datos['producto_id']!=$producto_id || $datos['proveedor_id']!=$proveedor_id){
			    $check_relacion=$this->ejecutarConsulta("SELECT producto_proveedor_id FROM producto_proveedor WHERE producto_id='$producto_id' AND proveedor_id='$proveedor_id'");
			    if($check_relacion->rowCount()>=1){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ya existe una relación entre este producto y proveedor",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
			}

		    $producto_proveedor_datos_up=[
				[
					"campo_nombre"=>"producto_id",
					"campo_marcador"=>":Producto",
					"campo_valor"=>$producto_id
				],
				[
					"campo_nombre"=>"proveedor_id",
					"campo_marcador"=>":Proveedor",
					"campo_valor"=>$proveedor_id
				],
				[
					"campo_nombre"=>"producto_proveedor_precio",
					"campo_marcador"=>":Precio",
					"campo_valor"=>$precio
				],
				[
					"campo_nombre"=>"producto_proveedor_unidad_medida",
					"campo_marcador"=>":UnidadMedida",
					"campo_valor"=>$unidad_medida
				]
			];

			$condicion=[
				"condicion_campo"=>"producto_proveedor_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("producto_proveedor",$producto_proveedor_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Relación actualizada",
					"texto"=>"Los datos de la relación se actualizaron correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos de la relación, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

















		/*----------  Controlador eliminar producto proveedor  ----------*/
		public function eliminarProductoProveedorControlador(){

			$id=$this->limpiarCadena($_POST['producto_proveedor_id']);

			# Verificando relación #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto_proveedor WHERE producto_proveedor_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado la relación en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    $eliminar_relacion=$this->eliminarRegistro("producto_proveedor","producto_proveedor_id",$id);

		    if($eliminar_relacion->rowCount()==1){
		        $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Relación eliminada",
					"texto"=>"La relación producto-proveedor ha sido eliminada del sistema correctamente",
					"icono"=>"success"
				];
		    }else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar la relación producto-proveedor, por favor intente nuevamente",
					"icono"=>"error"
				];
		    }

		    return json_encode($alerta);
		}

/*----------  Controlador listar producto proveedor  ----------*/
public function listarProductoProveedorControlador($pagina,$registros,$url,$busqueda,$proveedor){

    $pagina=$this->limpiarCadena($pagina);
    $registros=$this->limpiarCadena($registros);
    $proveedor=$this->limpiarCadena($proveedor);

    $url=$this->limpiarCadena($url);
    if($proveedor>0){
        $url=APP_URL.$url."/".$proveedor."/";
    }else{
        $url=APP_URL.$url."/";
    }

    $busqueda=$this->limpiarCadena($busqueda);
    $tabla="";

    $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
    $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

    $campos="producto_proveedor.producto_proveedor_id,producto.producto_nombre,proveedor.proveedor_nombre,producto_proveedor_precio,producto_proveedor_unidad_medida,pp_fecha_registro";

    if(isset($busqueda) && $busqueda!=""){

        $consulta_datos="SELECT $campos FROM producto_proveedor INNER JOIN producto ON producto_proveedor.producto_id=producto.producto_id INNER JOIN proveedor ON producto_proveedor.proveedor_id=proveedor.proveedor_id WHERE producto.producto_nombre LIKE '%$busqueda%' OR proveedor.proveedor_nombre LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(producto_proveedor_id) FROM producto_proveedor INNER JOIN producto ON producto_proveedor.producto_id=producto.producto_id INNER JOIN proveedor ON producto_proveedor.proveedor_id=proveedor.proveedor_id WHERE producto.producto_nombre LIKE '%$busqueda%' OR proveedor.proveedor_nombre LIKE '%$busqueda%'";

    }elseif($proveedor>0){

        $consulta_datos="SELECT $campos FROM producto_proveedor INNER JOIN producto ON producto_proveedor.producto_id=producto.producto_id INNER JOIN proveedor ON producto_proveedor.proveedor_id=proveedor.proveedor_id WHERE producto_proveedor.proveedor_id='$proveedor' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(producto_proveedor_id) FROM producto_proveedor WHERE proveedor_id='$proveedor'";

    }else{

        $consulta_datos="SELECT $campos FROM producto_proveedor INNER JOIN producto ON producto_proveedor.producto_id=producto.producto_id INNER JOIN proveedor ON producto_proveedor.proveedor_id=proveedor.proveedor_id ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(producto_proveedor_id) FROM producto_proveedor";

    }

    $datos = $this->ejecutarConsulta($consulta_datos);
    $datos = $datos->fetchAll();

    $total = $this->ejecutarConsulta($consulta_total);
    $total = (int) $total->fetchColumn();

    $numeroPaginas =ceil($total/$registros);

    if($total>=1 && $pagina<=$numeroPaginas){
        $contador=$inicio+1;
        $pag_inicio=$inicio+1;
        foreach($datos as $rows){
            // 1. Sanitizar y preparar datos para la función JS
            $producto_html = htmlspecialchars($rows['producto_nombre'], ENT_QUOTES, 'UTF-8');
            $proveedor_html = htmlspecialchars($rows['proveedor_nombre'], ENT_QUOTES, 'UTF-8');
            $unidad_html = htmlspecialchars($rows['producto_proveedor_unidad_medida'], ENT_QUOTES, 'UTF-8');
            // Usamos number_format para asegurar un formato numérico simple (ej: 12.50)
            $precio = number_format($rows['producto_proveedor_precio'], 2, '.', ''); 
            
            $tabla.='
            <article class="media pb-3 pt-3">
                <figure class="media-left">
                    <p class="image is-64x64">
                        <img src="'.APP_URL.'app/views/productos/default.png">
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>'.$contador.' - '.$producto_html.'</strong><br>
                            <strong>Proveedor:</strong> '.$proveedor_html.', 
                            <strong>Precio:</strong> $'.$precio.', 
                            <strong>Unidad:</strong> '.$unidad_html.', 
                            <strong>Fecha:</strong> '.$rows['pp_fecha_registro'].'
                        </p>
                    </div>
                    <div class="has-text-right">
                        
                        <a href="'.APP_URL.'productSupplierUpdate/'.$rows['producto_proveedor_id'].'/" class="button is-success is-rounded is-small">
                            <i class="fas fa-sync fa-fw"></i>
                        </a>

                        <form class="FormularioAjax is-inline-block" action="'.APP_URL.'app/ajax/productSupplierAjax.php" method="POST" autocomplete="off" >

                            <input type="hidden" name="modulo_productSupplier" value="eliminar">
                            <input type="hidden" name="producto_proveedor_id" value="'.$rows['producto_proveedor_id'].'">

                            <button type="submit" class="button is-danger is-rounded is-small">
                                <i class="far fa-trash-alt fa-fw"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </article>


            <hr>
            ';
            $contador++;
        }
        $pag_final=$contador-1;
    }else{
        if($total>=1){
            $tabla.='
            <p class="has-text-centered">No hay productos proveedores que mostrar en esta página</p>
            ';
        }else{
            $tabla.='
                <p class="has-text-centered">No hay productos proveedores registrados</p>
            ';
        }
    }

    ### Paginacion ###
    if($total>0 && $pagina<=$numeroPaginas){
        $tabla.='<p class="has-text-right">Mostrando productos proveedores <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

        $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
    }

    return $tabla;
}







}