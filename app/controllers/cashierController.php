<?php

    namespace app\controllers;
    use app\models\mainModel;

    class cashierController extends mainModel{

        /*----------  Controlador registrar caja  ----------*/
        public function registrarCajaControlador(){
            // 1. Recibir y limpiar datos del POST
            $numero = $this->limpiarCadena($_POST['caja_numero']);
            $nombre = $this->limpiarCadena($_POST['caja_nombre']);
            $efectivo = $this->limpiarCadena($_POST['caja_efectivo']);


            // 2. Verificaciones de campos obligatorios
            if($numero=="" || $nombre=="" || $efectivo==""){
                // En lugar de alertaError, simplemente retornamos falso si falta un campo.
                return false;
            }

            // 3. Verificaciones de formato (REGEX)
            if($this->verificarDatos("[0-9]{1,5}", $numero)){
                return false;
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ:# ]{3,70}", $nombre)){
                return false;
            }

            // 4. Formatear Efectivo Inicial
            // Reemplazar comas por puntos para asegurar formato decimal correcto para DB
            $efectivo = str_replace(",", ".", $efectivo); 
            
            if($this->verificarDatos("[0-9.]{1,25}", $efectivo)){
                return false;
            }
            
            // Convertir a float y asegurar que no sea negativo
            $efectivo = (float) $efectivo;
            if($efectivo < 0){
                return false;
            }


            // 5. Verificar que el número de caja no esté registrado
            $check_numero = $this->seleccionarDatos("Unico", "caja", "caja_numero", $numero);
            if($check_numero->rowCount() > 0){
                return false;
            }

            // 6. Verificar que el nombre/código de caja no esté registrado
            $check_nombre = $this->seleccionarDatos("Unico", "caja", "caja_nombre", $nombre);
            if($check_nombre->rowCount() > 0){
                return false;
            }

            // 7. Preparar datos para el registro
            $datos_caja_reg = [
                [
                    "campo_nombre" => "caja_numero",
                    "campo_marcador" => ":Numero",
                    "campo_valor" => $numero
                ],
                [
                    "campo_nombre" => "caja_nombre",
                    "campo_marcador" => ":Nombre",
                    "campo_valor" => $nombre
                ],
                [
                    "campo_nombre" => "caja_efectivo",
                    "campo_marcador" => ":Efectivo",
                    "campo_valor" => $efectivo // Se guarda como float
                ]
            ];

            // 8. Intentar guardar la caja en la base de datos
            $registrar_caja = $this->guardarDatos("caja", $datos_caja_reg);

            // 9. Verificar resultado de la inserción
            if($registrar_caja->rowCount() == 1){
                // Éxito: retorna true (o una respuesta JSON mínima de éxito si el frontend lo requiere)
                return true;
            }else{
                // Error en la inserción: retorna false
                return false;
            }
        }


        /*----------  Controlador listar cajas  ----------*/
        public function listarCajaControlador($pagina,$registros,$url,$busqueda){

            $pagina=$this->limpiarCadena($pagina);
            $registros=$this->limpiarCadena($registros);

            $url=$this->limpiarCadena($url);
            $url=APP_URL.$url."/";

            $busqueda=$this->limpiarCadena($busqueda);
            $tabla="";

            $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
            $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

            if(isset($busqueda) && $busqueda!=""){

                $consulta_datos="SELECT * FROM caja WHERE caja_numero LIKE '%$busqueda%' OR caja_nombre LIKE '%$busqueda%' ORDER BY caja_numero ASC LIMIT $inicio,$registros";

                $consulta_total="SELECT COUNT(caja_id) FROM caja WHERE caja_numero LIKE '%$busqueda%' OR caja_nombre LIKE '%$busqueda%'";

            }else{

                $consulta_datos="SELECT * FROM caja ORDER BY caja_numero ASC LIMIT $inicio,$registros";

                $consulta_total="SELECT COUNT(caja_id) FROM caja";

            }

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            $total = $this->ejecutarConsulta($consulta_total);
            $total = (int) $total->fetchColumn();

            $numeroPaginas =ceil($total/$registros);

            $tabla.='
                <div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr class="has-background-light has-text-weight-bold">
                            <th class="has-text-centered is-uppercase">NÚMERO</th>
                            <th class="has-text-centered is-uppercase">NOMBRE / CÓDIGO</th>
                            <th class="has-text-centered is-uppercase">EFECTIVO INICIAL</th>
                            <th class="has-text-centered is-uppercase">ACTUALIZAR</th>
                            <th class="has-text-centered is-uppercase">ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if($total>=1 && $pagina<=$numeroPaginas){
                $contador=$inicio+1;
                $pag_inicio=$inicio+1;
                foreach($datos as $rows){
                    
                    // ### INICIO DE LA MODIFICACIÓN CLAVE ###
                    // Usamos number_format directamente con constantes globales para el formato de moneda.
                    $efectivo_formato = MONEDA_SIMBOLO.number_format($rows['caja_efectivo'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                    // ### FIN DE LA MODIFICACIÓN CLAVE ###

                    $tabla.='
                        <tr class="has-text-centered" >
                            <td class="has-text-weight-bold">#'.$rows['caja_numero'].'</td>
                            <td>'.$rows['caja_nombre'].'</td>
                            <td class="has-text-weight-bold has-text-success">'.$efectivo_formato.'</td>
                            <td>
                                <a href="'.APP_URL.'cashierUpdate/'.$rows['caja_id'].'/" class="button is-success is-rounded is-small" title="Actualizar datos">
                                    <i class="fas fa-sync fa-fw"></i>
                                </a>
                            </td>
                            <td>';
                                if($rows['caja_id']!=1){ // Evitar eliminar la caja principal (ID 1)
                                    $tabla.='
                                    <form class="FormularioAjax" action="'.APP_URL.'app/ajax/cajaAjax.php" method="POST" autocomplete="off" >

                                        <input type="hidden" name="modulo_caja" value="eliminar">
                                        <input type="hidden" name="caja_id" value="'.$rows['caja_id'].'">

                                        <button type="submit" class="button is-danger is-rounded is-small" title="Eliminar caja">
                                            <i class="far fa-trash-alt fa-fw"></i>
                                        </button>
                                    </form>';
                                }else{
                                    $tabla.='
                                    <button type="button" class="button is-danger is-rounded is-small is-static" title="Caja principal no se puede eliminar">
                                        <i class="fas fa-ban fa-fw"></i>
                                    </button>';
                                }
                            $tabla.='
                            </td>
                        </tr>
                    ';
                    $contador++;
                }
                $pag_final=$contador-1;
            }else{
                if($total>=1){
                    $tabla.='
                        <tr class="has-text-centered" >
                            <td colspan="5">
                                <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                    ';
                }else{
                    $tabla.='
                        <tr class="has-text-centered" >
                            <td colspan="5">
                                <article class="message is-info mt-4 mb-4">
                                    <div class="message-body">
                                        <i class="fas fa-info-circle"></i> &nbsp; No hay registros de cajas en el sistema
                                    </div>
                                </article>
                            </td>
                        </tr>
                    ';
                }
            }

            $tabla.='</tbody></table></div>';

            ### Paginacion ###
            if($total>0 && $pagina<=$numeroPaginas){
                $tabla.='<p class="has-text-right">Mostrando cajas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

                $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
            }

            return $tabla;
        }


        /*----------  Controlador eliminar caja  ----------*/
        public function eliminarCajaControlador(){
            // 1. Obtener el ID de la caja a eliminar
            $id = $this->limpiarCadena($_POST['caja_id']);

            // 2. Verificar que el ID exista
            if($id==""){
                return false;
            }

            // 3. Seleccionar la caja para verificar si existe
            $datos = $this->seleccionarDatos("Unico","caja","caja_id",$id);

            if($datos->rowCount()==0){
                return false;
            }

            $datos=$datos->fetch();

            // 4. No permitir eliminar la caja principal con ID 1 (o la que se designe como principal/por defecto)
            if($datos['caja_id']==1){
                return false;
            }

            // 5. Verificar que la caja no tenga ventas asociadas (relacion con el modulo venta_caja)
            // Se asume que existe una columna 'caja_id' en la tabla 'venta' que debe estar en 0 (caja eliminada/sin asignar) o NULL.
            // Para este ejemplo, verificaremos si hay registros de VENTAS asociados a esta caja.
            $check_ventas = $this->ejecutarConsulta("SELECT caja_id FROM venta WHERE caja_id='$id' LIMIT 1");
            
            if($check_ventas->rowCount() > 0){
                // Si hay ventas asociadas, se recomienda no eliminar. 
                // En un sistema real, se debería deshabilitar o reasignar las ventas a otra caja.
                return false; 
            }

            // 6. Eliminar la caja
            $eliminarCaja=$this->eliminarRegistro("caja","caja_id",$id);

            if($eliminarCaja->rowCount()==1){
                // Éxito: retorna true para que el frontend recargue
                return true;
            }else{
                // Error en la eliminación
                return false;
            }
        }


        /*----------  Controlador actualizar caja  ----------*/
        public function actualizarCajaControlador(){
            // 1. Recoger el ID de la caja y limpiarlo (viene oculto en el formulario de actualización)
            $id = $this->limpiarCadena($_POST['caja_id']);

            // 2. Verificar que la caja exista en la BD
            $datos = $this->seleccionarDatos("Unico","caja","caja_id",$id);
            if($datos->rowCount()==0){
                return false;
            }
            $datos_caja_antigua=$datos->fetch();

            // 3. Recibir y limpiar los nuevos datos
            $numero = $this->limpiarCadena($_POST['caja_numero']);
            $nombre = $this->limpiarCadena($_POST['caja_nombre']);
            $efectivo = $this->limpiarCadena($_POST['caja_efectivo']);

            // 4. Verificaciones de campos obligatorios
            if($numero=="" || $nombre=="" || $efectivo==""){
                return false;
            }

            // 5. Verificaciones de formato (REGEX)
            if($this->verificarDatos("[0-9]{1,5}", $numero)){
                return false;
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ:# ]{3,70}", $nombre)){
                return false;
            }

            // 6. Formatear y validar Efectivo Inicial
            $efectivo = str_replace(",", ".", $efectivo); 
            if($this->verificarDatos("[0-9.]{1,25}", $efectivo)){
                return false;
            }
            $efectivo = (float) $efectivo;
            if($efectivo < 0){
                return false;
            }
            
            // 7. Verificar que el Número de caja no esté duplicado, EXCLUYENDO la caja actual
            if($datos_caja_antigua['caja_numero']!=$numero){
                $check_numero = $this->seleccionarDatos("Unico", "caja", "caja_numero", $numero);
                if($check_numero->rowCount() > 0){
                    return false;
                }
            }

            // 8. Verificar que el Nombre de caja no esté duplicado, EXCLUYENDO la caja actual
            if($datos_caja_antigua['caja_nombre']!=$nombre){
                $check_nombre = $this->seleccionarDatos("Unico", "caja", "caja_nombre", $nombre);
                if($check_nombre->rowCount() > 0){
                    return false;
                }
            }

            // 9. Preparar datos para la actualización
            $datos_caja_act = [
                [
                    "campo_nombre" => "caja_numero",
                    "campo_marcador" => ":Numero",
                    "campo_valor" => $numero
                ],
                [
                    "campo_nombre" => "caja_nombre",
                    "campo_marcador" => ":Nombre",
                    "campo_valor" => $nombre
                ],
                [
                    "campo_nombre" => "caja_efectivo",
                    "campo_marcador" => ":Efectivo",
                    "campo_valor" => $efectivo // Se guarda como float
                ]
            ];

            // 10. Ejecutar la actualización
            $condicion = [
                "condicion_campo" => "caja_id",
                "condicion_marcador" => ":ID",
                "condicion_valor" => $id
            ];

            if($this->actualizarDatos("caja", $datos_caja_act, $condicion)){
                // Éxito: retorna true
                return true;
            }else{
                // Error en la actualización
                return false;
            }
        }

    }