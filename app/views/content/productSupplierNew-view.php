<div class="container is-fluid mb-6">
    <h1 class="title">Productos y Proveedores</h1>
    <h2 class="subtitle"><i class="fas fa-link fa-fw"></i> &nbsp; Asignar Proveedor a Producto</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productSupplierAjax.php" method="POST" autocomplete="off" >

        <input type="hidden" name="modulo_productSupplier" value="registrar">

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Producto <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select is-fullwidth">
                        <select name="producto_id" required >
                            <option value="" selected="" >Seleccione un Producto</option>
                            <?php
                                use app\models\mainModel;
                                $insMainModel = new mainModel();

                                $datos_productos=$insMainModel->seleccionarDatos("Normal","producto","producto_id,producto_nombre,producto_codigo",0);

                                while($campos_producto=$datos_productos->fetch()){
                                    echo '<option value="'.$campos_producto['producto_id'].'">'.$campos_producto['producto_codigo'].' - '.$campos_producto['producto_nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Proveedor <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select is-fullwidth">
                        <select name="proveedor_id" required >
                            <option value="" selected="" >Seleccione un Proveedor</option>
                            <?php
                                $datos_proveedores=$insMainModel->seleccionarDatos("Normal","proveedor","proveedor_id,proveedor_nombre,proveedor_contacto",0);

                                while($campos_proveedor=$datos_proveedores->fetch()){
                                    echo '<option value="'.$campos_proveedor['proveedor_id'].'">'.$campos_proveedor['proveedor_nombre'].' (Contacto: '.$campos_proveedor['proveedor_contacto'].')</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-third">
                <div class="control">
                    <label>Precio de compra a este proveedor <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_proveedor_precio" pattern="[0-9.]{1,25}" maxlength="25" required>
                </div>
            </div>

            <div class="column is-one-third">
                <div class="control">
                    <label>Unidad de Medida del Proveedor (Ej: Kilos, Caja x 12) <?php echo CAMPO_OBLIGATORIO; ?></label>
                    
                    <input class="input" type="text" name="producto_proveedor_unidad_medida" required
                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\/ -]{1,30}" maxlength="30" > 
                </div>
            </div>
        </div>

        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Registrar Relación</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
</div>