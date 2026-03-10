<div class="container is-fluid mb-6">
    <?php 
        // Asegúrate de que $insLogin sea una instancia del controlador o modelo que maneja la lógica de la sesión y limpieza
        // Si estás usando userController, quizás debas instanciarlo primero aquí si no lo has hecho.

        $id=$insLogin->limpiarCadena($url[1]);

        if($id==$_SESSION['id']){ 
    ?>
    <h1 class="title">Mi cuenta</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar cuenta</h2>
    <?php }else{ ?>
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar Empleado </h2>
    <?php } ?>
</div>
<div class="container pb-6 pt-6" style="margin-top: -190px;">
    <?php
        use app\controllers\userController; // Asegúrate de que el controlador esté disponible
        
        $insUsuario = new userController(); // Instanciar el controlador de usuario si $insLogin no es suficiente.

        // NOTA: Usaremos $insUsuario->seleccionarDatos si es el controlador principal o se hereda de mainModel
        $datos=$insUsuario->seleccionarDatos("Unico","usuario","usuario_id",$id);

        if($datos->rowCount()==1){
            $datos=$datos->fetch();
    ?>

    <div class="columns is-flex is-justify-content-center">
        <figure class="image is-128x128">
            <?php
                if(is_file("./app/views/fotos/".$datos['usuario_foto'])){
                    echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/'.$datos['usuario_foto'].'">';
                }else{
                    echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/default.png">';
                }
            ?>
        </figure>
    </div>

    <h2 class="title has-text-centered"><?php echo $datos['usuario_nombre']." ".$datos['usuario_apellido']; ?></h2>

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" >

        <input type="hidden" name="modulo_usuario" value="actualizar">
        <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>">

        <p class="has-text-centered is-size-5 mb-4 mt-5 has-text-weight-bold">Datos de Contacto y Personales</p>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombres <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['usuario_nombre']; ?>" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellidos <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['usuario_apellido']; ?>" required >
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Email</label>
                    <input class="input" type="email" name="usuario_email" maxlength="50" value="<?php echo $datos['usuario_email']; ?>" >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Cédula/ID</label>
                    <input class="input" type="text" name="usuario_cedula" pattern="[0-9]{5,20}" maxlength="35" value="<?php echo $datos['usuario_cedula']; ?>" >
                </div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Teléfono</label>
                    <input class="input" type="text" name="usuario_telefono" pattern="[0-9()+ -]{7,20}" maxlength="20" value="<?php echo $datos['usuario_telefono']; ?>" >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Dirección</label>
                    <input class="input" type="text" name="usuario_direccion" maxlength="70" value="<?php echo $datos['usuario_direccion']; ?>" >
                </div>
            </div>
        </div>

        <p class="has-text-centered is-size-5 mb-4 mt-5 has-text-weight-bold">Acceso y Puesto de Trabajo</p>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Usuario <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" value="<?php echo $datos['usuario_usuario']; ?>" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Cargo <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="usuario_cargo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}" maxlength="30" value="<?php echo $datos['usuario_cargo']; ?>" required >
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <label>Caja de ventas <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select">
                    <select name="usuario_caja">
                        <?php
                            // Se asume que $insUsuario es una instancia con acceso a MainModel/DB
                            $datos_cajas=$insUsuario->seleccionarDatos("Normal","caja","*",0);

                            while($campos_caja=$datos_cajas->fetch()){
                                if($campos_caja['caja_id']==$datos['caja_id']){
                                    echo '<option value="'.$campos_caja['caja_id'].'" selected="" >Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].' (Actual)</option>';
                                }else{
                                    echo '<option value="'.$campos_caja['caja_id'].'">Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        
        <hr>
        <p class="has-text-centered">
            SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
        </p>
        <br>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nueva clave</label>
                    <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Repetir nueva clave</label>
                    <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
                </div>
            </div>
        </div>
        <br><br><br>
        <p class="has-text-centered">
            Para poder actualizar los datos de este usuario por favor ingrese su **USUARIO** y **CLAVE** con la que ha iniciado sesión
        </p>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Usuario <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Clave <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
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