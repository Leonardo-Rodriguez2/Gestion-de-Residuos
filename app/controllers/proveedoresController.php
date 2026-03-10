<?php
    namespace app\controllers;
    use app\models\mainModel;

    class ProveedoresController extends mainModel{

        /*----------  Controlador registrar proveedor  ----------*/
        public function registrarProveedorControlador(){

            # Almacenando datos #
            $nombre=$this->limpiarCadena($_POST['proveedor_nombre']);
            $contacto=$this->limpiarCadena($_POST['proveedor_contacto']);
            $telefono=$this->limpiarCadena($_POST['proveedor_telefono']);
            $email=$this->limpiarCadena($_POST['proveedor_email']);
            $direccion=$this->limpiarCadena($_POST['proveedor_direccion']);

            # Verificando campos obligatorios #
            if($nombre=="" || $contacto=="" || $telefono==""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No has llenado todos los campos que son obligatorios (Nombre, Contacto, Teléfono)",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando formato de campos #
            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}",$nombre)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El Nombre del Proveedor no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}",$contacto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El Nombre de Contacto no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[0-9()+]{8,20}",$telefono)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El Teléfono no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($email!=""){
                if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"La Dirección no coincide con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
            
            # Verificando email #
            if($email!="" && !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ha ingresado un correo electrónico no válido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Preparando datos para el modelo #
            $proveedor_datos_reg=[
                [
                    "campo_nombre"=>"proveedor_nombre",
                    "campo_marcador"=>":Nombre",
                    "campo_valor"=>$nombre
                ],
                [
                    "campo_nombre"=>"proveedor_contacto",
                    "campo_marcador"=>":Contacto",
                    "campo_valor"=>$contacto
                ],
                [
                    "campo_nombre"=>"proveedor_telefono",
                    "campo_marcador"=>":Telefono",
                    "campo_valor"=>$telefono
                ],
                [
                    "campo_nombre"=>"proveedor_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$email
                ],
                [
                    "campo_nombre"=>"proveedor_direccion",
                    "campo_marcador"=>":Direccion",
                    "campo_valor"=>$direccion
                ]
            ];

            $registrar_proveedor=$this->guardarDatos("proveedor",$proveedor_datos_reg);

            if($registrar_proveedor->rowCount()==1){
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Proveedor registrado",
                    "texto"=>"El proveedor ".$nombre." se registró con éxito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el proveedor, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }

            return json_encode($alerta);
        }

        /*----------  Controlador listar proveedor (Ahora completo) ----------*/
        public function listarProveedorControlador($pagina,$registros,$url,$busqueda){
            
            $pagina=$this->limpiarCadena($pagina);
            $registros=$this->limpiarCadena($registros);
            
            $url=APP_URL.$url."/"; 

            $busqueda=$this->limpiarCadena($busqueda);
            $tabla="";

            $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
            $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

            $campos="proveedor_id, proveedor_nombre, proveedor_contacto, proveedor_telefono, proveedor_email, proveedor_direccion";

            if(isset($busqueda) && $busqueda!=""){

                $consulta_datos="SELECT $campos FROM proveedor WHERE proveedor_nombre LIKE '%$busqueda%' OR proveedor_contacto LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%' ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

                $consulta_total="SELECT COUNT(proveedor_id) FROM proveedor WHERE proveedor_nombre LIKE '%$busqueda%' OR proveedor_contacto LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%'";

            }else{

                $consulta_datos="SELECT $campos FROM proveedor ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

                $consulta_total="SELECT COUNT(proveedor_id) FROM proveedor";

            }
            
            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            $total = $this->ejecutarConsulta($consulta_total);
            $total = (int) $total->fetchColumn();

            $numeroPaginas =ceil($total/$registros);

            if($total>=1 && $pagina<=$numeroPaginas){
                $contador=$inicio+1;
                $pag_inicio=$inicio+1;
                
                $tabla.='
                    <div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr class="has-text-centered">
                                <th>#</th>
                                <th>NOMBRE</th>
                                <th>CONTACTO</th>
                                <th>TELÉFONO</th>
                                <th>EMAIL</th>
                                <th>DIRECCIÓN</th>
                                <th colspan="3">OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                ';

                foreach($datos as $rows){
                    
                    $tabla.='
                    <tr class="has-text-centered">
                        <td>'.$contador.'</td>
                        <td>'.$rows['proveedor_nombre'].'</td>
                        <td>'.$rows['proveedor_contacto'].'</td>
                        <td>'.$rows['proveedor_telefono'].'</td>
                        <td>'.$rows['proveedor_email'].'</td>
                        <td>'.$rows['proveedor_direccion'].'</td>
                        
                        <td colspan="3">
                            <a href="'.APP_URL.'proveedorUpdate/'.$rows['proveedor_id'].'/" class="button is-success is-small is-rounded" title="Actualizar">
                                <i class="fas fa-sync fa-fw"></i>
                            </a>
                            <a href="'.APP_URL.'proveedorProducts/'.$rows['proveedor_id'].'/" class="button is-info is-small is-rounded" title="Ver Productos Suministrados">
                                <i class="fas fa-box-open fa-fw"></i>
                            </a>

                            <form class="FormularioAjax is-inline-block" action="'.APP_URL.'app/ajax/proveedoresAjax.php" method="POST" autocomplete="off" >

                                <input type="hidden" name="modulo_proveedor" value="eliminar">
                                <input type="hidden" name="proveedor_id" value="'.$rows['proveedor_id'].'">

                                <button type="submit" class="button is-danger is-rounded is-small">
                                    <i class="far fa-trash-alt fa-fw"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    ';
                    $contador++;
                }
                
                $tabla.='</tbody></table></div>';
                $pag_final=$contador-1;
            }else{
                if($total>=1){
                    $tabla.='
                        <p class="has-text-centered pb-6"><i class="far fa-hand-point-down fa-5x"></i></p>
                        <p class="has-text-centered">
                            <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                                Haga clic acá para recargar el listado
                            </a>
                        </p>
                    ';
                }else{
                    $tabla.='
                        <p class="has-text-centered pb-6"><i class="far fa-grin-beam-sweat fa-5x"></i></p>
                        <p class="has-text-centered">No hay proveedores registrados en el sistema</p>
                    ';
                }
            }

            ### Paginacion ###
            if($total>0 && $pagina<=$numeroPaginas){
                $tabla.='<p class="has-text-right">Mostrando proveedores <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

                $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
            }

            return $tabla;
        }


        /*----------  Controlador listar productos por proveedor (Relación Producto-Proveedor)  ----------*/
        // Esta función permanece correcta para la relación.
        public function listarProductosPorProveedorControlador($id_proveedor){
            
            $id_proveedor=$this->limpiarCadena($id_proveedor);

            // 1. Verificar si el proveedor existe
            $check_proveedor=$this->ejecutarConsulta("SELECT proveedor_nombre FROM proveedor WHERE proveedor_id='$id_proveedor'");
            if($check_proveedor->rowCount()<=0){
                return "<p class='has-text-centered title is-4 has-text-danger'>No se ha encontrado el proveedor especificado.</p>";
            }
            $proveedor_info = $check_proveedor->fetch();
            $proveedor_nombre = $proveedor_info['proveedor_nombre'];
            
            // 2. Consulta de productos asociados a ese proveedor (USANDO INNER JOIN)
            $productos = $this->ejecutarConsulta("
                SELECT 
                    p.producto_nombre, 
                    p.producto_codigo, 
                    p.producto_stock_total,
                    pp.producto_proveedor_id, 
                    pp.producto_proveedor_precio,
                    pp.producto_proveedor_unidad_medida,
                    pp.pp_fecha_registro
                FROM 
                    producto_proveedor pp
                INNER JOIN 
                    producto p ON pp.producto_id = p.producto_id
                WHERE 
                    pp.proveedor_id = '$id_proveedor'
                ORDER BY 
                    p.producto_nombre ASC
            ");
            
            $tabla = '';
            if($productos->rowCount() > 0){
                $tabla .= '
                    <h4 class="title has-text-centered is-4">Productos suministrados por: **'.$proveedor_nombre.'**</h4>
                    <div class="table-container">
                        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                            <thead>
                                <tr class="has-text-centered">
                                    <th>#</th>
                                    <th>CÓDIGO</th>
                                    <th>PRODUCTO</th>
                                    <th>STOCK ACTUAL</th>
                                    <th>PRECIO PROVEEDOR</th>
                                    <th>UNIDAD DE MEDIDA</th>
                                    <th>REGISTRO PP</th>
                                </tr>
                            </thead>
                            <tbody>
                ';

                $contador = 1;
                while($row = $productos->fetch()){
                    $precio_format = number_format($row['producto_proveedor_precio'], 2, '.', ',');

                    $tabla.='
                        <tr class="has-text-centered">
                            <td>'.$contador.'</td>
                            <td>'.$row['producto_codigo'].'</td>
                            <td>'.$row['producto_nombre'].'</td>
                            <td>'.$row['producto_stock_total'].'</td>
                            <td>$ '.$precio_format.'</td>
                            <td>'.$row['producto_proveedor_unidad_medida'].'</td>
                            <td>'.$row['pp_fecha_registro'].'</td>
                        </tr>
                    ';
                    $contador++;
                }

                $tabla.='</tbody></table></div>';
            } else {
                $tabla='
                    <p class="has-text-centered pb-6"><i class="far fa-grin-beam-sweat fa-5x"></i></p>
                    <p class="has-text-centered title is-5">
                        El proveedor **'.$proveedor_nombre.'** no tiene productos asociados en este momento.
                    </p>
                ';
            }

            return $tabla;
        }


        // *** NOTA: FALTAN FUNCIONES ELIMINAR Y ACTUALIZAR PROVEEDOR ***
        // Si las necesitas, proporciona el código o pídeme que las agregue.

    } // Fin de la clase ProveedoresController