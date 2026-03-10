<div class="container is-fluid mb-6">
    <h1 class="title">Proveedores</h1>
    <h2 class="subtitle"><i class="fas fa-truck-moving fa-fw"></i> &nbsp; Nuevo proveedor</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/proveedorAjax.php" method="POST" autocomplete="off" >

        <input type="hidden" name="modulo_proveedor" value="registrar">

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre del Proveedor (Empresa) <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_nombre"  maxlength="100" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Persona de Contacto <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_contacto" maxlength="100" required >
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Teléfono <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="proveedor_telefono" maxlength="20" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Email</label>
                    <input class="input" type="email" name="proveedor_email" maxlength="50" >
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Dirección</label>
                    <input class="input" type="text" name="proveedor_direccion" maxlength="150" >
                </div>
            </div>
        </div>
        
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
</div>