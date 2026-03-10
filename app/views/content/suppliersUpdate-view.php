<div class="container is-fluid mb-6">
    <h1 class="title">Proveedores</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt fa-fw"></i> &nbsp; Actualizar proveedor</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
    
        // Incluye el botón de retroceso (asumiendo que $url[0] es la vista actual)
        include "./app/views/inc/btn_back.php";

        // Importante: Asumiendo que $insLogin está disponible (o cámbialo a $insMainModel o el que uses para seleccionar datos)
        // Usaremos $insMainModel para la consulta, ya que $insLogin suele ser solo para la sesión.
        use app\models\mainModel;
        $insMainModel = new mainModel();

        // Limpiar y obtener el ID del proveedor de la URL (ej: /suppliersUpdate/10/)
        $id=$insMainModel->limpiarCadena($url[1]);

        // Consulta para obtener los datos del proveedor específico
        $datos=$insMainModel->seleccionarDatos("Unico","proveedor","proveedor_id",$id);

        if($datos->rowCount()==1){
            $datos=$datos->fetch();
    ?>
    
    <h2 class="title has-text-centered">Actualizando: <?php echo $datos['proveedor_nombre']; ?></h2>

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/proveedorAjax.php" method="POST" autocomplete="off" >

        <input type="hidden" name="modulo_proveedor" value="actualizar">
        <input type="hidden" name="proveedor_id" value="<?php echo $datos['proveedor_id']; ?>">

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre del Proveedor (Empresa) <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_nombre" 
                        value="<?php echo $datos['proveedor_nombre']; ?>" 
                        pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\\- ]{1,100}" 
                        maxlength="100" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Persona de Contacto <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_contacto" 
                        value="<?php echo $datos['proveedor_contacto']; ?>" 
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,100}" 
                        maxlength="100" required >
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Teléfono <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_telefono" 
                        value="<?php echo $datos['proveedor_telefono']; ?>" 
                        pattern="[0-9\\(\\)\\+ ]{1,20}" 
                        maxlength="20" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Email</label>
                    <input class="input" type="email" name="proveedor_email" 
                        value="<?php echo $datos['proveedor_email']; ?>" 
                        maxlength="50" >
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Dirección</label>
                    <input class="input" type="text" name="proveedor_direccion" 
                        value="<?php echo $datos['proveedor_direccion']; ?>" 
                        pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\\- ]{1,150}" 
                        maxlength="150" >
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