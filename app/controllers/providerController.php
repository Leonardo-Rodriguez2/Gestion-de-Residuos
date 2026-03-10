<?php

    namespace app\controllers;
    use app\models\mainModel;
    use FPDF;

    class providerController extends mainModel{

        /*----------  Controlador registrar proveedor  ----------*/
        public function registrarProveedorControlador(){

            # Almacenando datos#
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

            # Verificando integridad de los datos #
            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}",$nombre)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El NOMBRE no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}",$contacto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El NOMBRE DE CONTACTO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[0-9()+ ]{1,20}",$telefono)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El TELÉFONO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($email!=""){
                /* MODIFICACIÓN: Se añade @ y _ a la expresión regular para permitir correos electrónicos */
                if($this->verificarDatos("[a-zA-Z0-9.@_]{1,50}",$email)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL no coincide con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
                # Comprobando email con filtro nativo de PHP #
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Ha ingresado un correo electrónico no válido",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            if($direccion!=""){
                if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"La DIRECCIÓN no coincide con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
            
            # Comprobando nombre de proveedor #
            $check_nombre=$this->ejecutarConsulta("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre='$nombre'");
            if($check_nombre->rowCount()>=1){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El nombre de proveedor que ha ingresado ya se encuentra registrado en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Preparando datos para el registro #
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

        /*----------  Controlador actualizar proveedor  ----------*/
        public function actualizarProveedorControlador(){

            $id=$this->limpiarCadena($_POST['proveedor_id']);

            # Verificando proveedor #
            $datos=$this->ejecutarConsulta("SELECT * FROM proveedor WHERE proveedor_id='$id'");
            if($datos->rowCount()<=0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos encontrado el proveedor en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datos=$datos->fetch();
            }

            # Almacenando datos#
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

            # Verificando integridad de los datos #
            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}",$nombre)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El NOMBRE no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}",$contacto)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El NOMBRE DE CONTACTO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[0-9()+ ]{1,20}",$telefono)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El TELÉFONO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($email!=""){
                /* MODIFICACIÓN: Se añade @ y _ a la expresión regular para permitir correos electrónicos */
                if($this->verificarDatos("[a-zA-Z0-9.@_]{1,50}",$email)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El EMAIL no coincide con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
                # Comprobando email con filtro nativo de PHP #
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"Ha ingresado un correo electrónico no válido",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            if($direccion!=""){
                if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"La DIRECCIÓN no coincide con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            # Comprobando nombre de proveedor #
            if($datos['proveedor_nombre']!=$nombre){
                $check_nombre=$this->ejecutarConsulta("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre='$nombre'");
                if($check_nombre->rowCount()>=1){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El nombre de proveedor que ha ingresado ya se encuentra registrado en el sistema",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            # Preparando datos para la actualización #
            $proveedor_datos_up=[
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

            $condicion=[
                "condicion_campo"=>"proveedor_id",
                "condicion_marcador"=>":ID",
                "condicion_valor"=>$id
            ];

            if($this->actualizarDatos("proveedor",$proveedor_datos_up,$condicion)){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Proveedor actualizado",
                    "texto"=>"Los datos del proveedor '".$datos['proveedor_nombre']."' se actualizaron correctamente",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos podido actualizar los datos del proveedor '".$datos['proveedor_nombre']."', por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }

            return json_encode($alerta);
        }

        /*----------  Controlador eliminar proveedor  ----------*/
        public function eliminarProveedorControlador(){
            
            $id=$this->limpiarCadena($_POST['proveedor_id']);

            # Verificando proveedor #
            $datos=$this->ejecutarConsulta("SELECT * FROM proveedor WHERE proveedor_id='$id'");
            if($datos->rowCount()<=0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El proveedor que intenta eliminar no existe en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $datos=$datos->fetch();
            }

            # Verificando productos asociados a este proveedor #
            $check_productos=$this->ejecutarConsulta("SELECT proveedor_id FROM producto_proveedor WHERE proveedor_id='$id' LIMIT 1");
            if($check_productos->rowCount()>=1){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El proveedor ".$datos['proveedor_nombre']." tiene productos asociados y no puede ser eliminado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $eliminar_proveedor=$this->eliminarRegistro("proveedor","proveedor_id",$id);

            if($eliminar_proveedor->rowCount()==1){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Proveedor eliminado",
                    "texto"=>"El proveedor ".$datos['proveedor_nombre']." ha sido eliminado correctamente del sistema",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo eliminar el proveedor, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }

            return json_encode($alerta);
        }





        // *** Este código debe ser añadido dentro de la clase providerController en providerController.php ***

/*----------  Controlador listar proveedores  ----------*/
public function listarProveedorControlador($pagina,$registros,$url,$busqueda){

    $pagina=$this->limpiarCadena($pagina);
    $registros=$this->limpiarCadena($registros);

    $url=$this->limpiarCadena($url);
    $url=APP_URL.$url."/";

    $busqueda=$this->limpiarCadena($busqueda);
    $tabla="";

    $pagina = (is_numeric($pagina) && $pagina>0) ? $pagina : 1;
    $inicio = ($pagina>0) ? (($pagina * $registros) - $registros) : 0;

    if(isset($busqueda) && $busqueda!=""){

        $consulta_datos="SELECT * FROM proveedor WHERE proveedor_nombre LIKE '%$busqueda%' OR proveedor_contacto LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%' ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(proveedor_id) FROM proveedor WHERE proveedor_nombre LIKE '%$busqueda%' OR proveedor_contacto LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' OR proveedor_email LIKE '%$busqueda%'";

    }else{

        $consulta_datos="SELECT * FROM proveedor ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";

        $consulta_total="SELECT COUNT(proveedor_id) FROM proveedor";

    }

    $datos = $this->ejecutarConsulta($consulta_datos);
    $datos_total = $this->ejecutarConsulta($consulta_total);

    $total = (int) $datos_total->fetchColumn(); // Obtiene el total de registros
    $numeroPaginas = ceil($total/$registros); // Calcula el número de páginas

    $tabla.='
        <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Nombre (Empresa)</th>
                    <th class="has-text-centered">Contacto</th>
                    <th class="has-text-centered">Teléfono</th>
                    <th class="has-text-centered">Email</th>
                    <th class="has-text-centered">Dirección</th>
                    <th class="has-text-centered" colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
    ';

    if($total>=1 && $datos->rowCount()>=1){
        $contador=$inicio+1;
        $pag_inicio=$inicio;

        while($rows = $datos->fetch()){
            $tabla.='
                <tr class="has-text-centered">
                    <td>'.$contador.'</td>
                    <td>'.$rows['proveedor_nombre'].'</td>
                    <td>'.$rows['proveedor_contacto'].'</td>
                    <td>'.$rows['proveedor_telefono'].'</td>
                    <td>'.($rows['proveedor_email'] ? $rows['proveedor_email'] : 'N/A').'</td>
                    <td>'.($rows['proveedor_direccion'] ? $rows['proveedor_direccion'] : 'N/A').'</td>
                    <td>
                        <a href="'.APP_URL.'suppliersUpdate/'.$rows['proveedor_id'].'/" class="button is-link is-rounded is-small">
                            <i class="fas fa-edit fa-fw"></i>
                        </a>
                    </td>
                    <td>
                        <form class="FormularioAjax" action="'.APP_URL.'app/ajax/proveedorAjax.php" method="POST" autocomplete="off" >

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
    }else{
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="8">
                    '.($busqueda=="" ? 'No hay proveedores registrados' : 'No se encontraron resultados para la búsqueda: <strong>"'.$busqueda.'"</strong>').'
                </td>
            </tr>
        ';
    }

    $tabla.='</tbody></table></div>';

    /*---------- Paginacion ----------*/
    if($total > 0 && $numeroPaginas > 1){
        $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,5);
    }

    return $tabla;
}




}