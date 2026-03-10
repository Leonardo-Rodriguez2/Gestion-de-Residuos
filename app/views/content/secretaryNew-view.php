<?php
    // NOTA: Se asume que la constante CAMPO_OBLIGATORIO está definida en un archivo de configuración.
    // Si no está definida, se usa un valor por defecto para asegurar que la vista no falle.
    if (!defined('CAMPO_OBLIGATORIO')) {
        define('CAMPO_OBLIGATORIO', '(Obligatorio)');
    }
?>

<div class="container is-fluid" style=" margin-top: -30px;">
    <!-- Encabezado de la vista replicando el estilo solicitado -->
    <div class="header-section" style="padding: 10px 0 10px 0; border-bottom: 2px solid #3498db; text-align: center;">
        <h1 class="title is-5 subtitle" style="color: #2c3e50; font-weight: 700;">
            <i class="fas fa-users fa-2x has-text-info"></i> &nbsp; Nuevo Usuario 
        </h1>
    </div>
</div>

<!-- Contenedor principal para simular el card con estilos en línea (basados en el CSS de tu ejemplo) -->
<div class="page-wrapper" style="display: flex; justify-content: center; padding: 10px 0;">
    <div class="user-form-card" style="
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 1200px; /* Ancho que simula el layout de ancho completo */
        padding: 40px; 
    ">

        <!-- Formulario con la clase FormularioAjax para manejo asíncrono -->
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

            <input type="hidden" name="modulo_usuario" value="registrar">
            
            <!-- Fila 1: Nombres, Apellidos, Direccion -->
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Nombres <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Apellidos <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Dirección</label>
                        <input class="input" type="text" name="usuario_direccion" maxlength="70" >
                    </div>
                </div>
            </div>
            
            <!-- Fila 2: Usuario, Email, Telefono -->
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Usuario <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Email</label>
                        <input class="input" type="email" name="usuario_email" maxlength="70" >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Número de Teléfono</label>
                        <!-- Se usa text para permitir formatos como +58 412... -->
                        <input class="input" type="text" name="usuario_telefono" pattern="[0-9()+ -]{8,20}" maxlength="20" > 
                    </div>
                </div>
            </div>
            
            <!-- Fila 3: Cédula, Clave 1, Clave 2 -->
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Cédula</label>
                        <input class="input" type="text" name="usuario_cedula" pattern="[0-9-]{7,20}" maxlength="20" >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Clave <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Repetir clave <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
                    </div>
                </div>
            </div>
            
            <!-- Fila 4: Foto, Caja, Cargo -->
            <div class="columns">
                <div class="column is-half">
                    <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Foto de Perfil</label>
                    <!-- Contenedor de la foto de perfil replicando photo-upload-box -->
                    <div class="file has-name is-boxed is-small" style="
                        border: 1px solid #dbdbdb;
                        border-radius: 6px;
                        padding: 15px;
                        background-color: #fcfcfc;
                        text-align: center;
                    ">
                        <label class="file-label">
                            <input class="file-input" type="file" name="usuario_foto" accept=".jpg, .png, .jpeg" >
                            <span class="file-cta is-info">
                                <span class="file-icon"><i class="fas fa-upload"></i></span>
                                <span class="file-label">Subir Foto</span>
                            </span>
                            <span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
                        </label>
                    </div>
                </div>

                <div class="column">
                    <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Caja de ventas <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label><br>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="usuario_caja" required>
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    /*
                                        NOTAS DE CÓDIGO:
                                        Se mantiene la lógica de la consulta a la base de datos que estaba en tu ejemplo.
                                        Se necesita que el objeto $insLogin esté disponible para funcionar.
                                    */
                                    if(isset($insLogin)){
                                        $datos_cajas=$insLogin->seleccionarDatos("Normal","caja","*",0);

                                        if ($datos_cajas->rowCount() > 0) {
                                            while($campos_caja=$datos_cajas->fetch()){
                                                echo '<option value="'.$campos_caja['caja_id'].'">Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].'</option>';
                                            }
                                        } else {
                                            echo '<option value="" disabled>No hay cajas registradas</option>';
                                        }
                                    } else {
                                        // Opciones de simulación si el objeto $insLogin no está disponible
                                        echo '<option value="1">Caja No. 1 - Principal</option>';
                                        echo '<option value="2">Caja No. 2 - Secundaria</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="column">
                    <label style="font-weight: 600; color: #34495e; margin-bottom: 5px; display: block; font-size: 0.95rem;">Cargo del Empleado<span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span></label><br>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="usuario_cargo" required>
                                <option value="" selected="" >Seleccione una opción</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Secretario">Secretario</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="has-text-centered pt-4 has-text-grey-light">
                <small>Los campos marcados con <span class="required-text" style="color: #e74c3c; font-size: 0.8rem;"><?php echo CAMPO_OBLIGATORIO; ?></span> son obligatorios</small>
            </p>

            <!-- Botones de acción replicando buttons-group -->
            <div class="buttons-group has-text-centered" style="padding-top: 20px; border-top: 1px dashed #e0e0e0;">
                <button type="reset" class="button is-link is-light is-rounded is-medium"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                <button type="submit" class="button is-info is-rounded is-medium"><i class="far fa-save"></i> &nbsp; Guardar</button>
            </div>
            
        </form>
    </div>
</div>

<!-- Script para las alertas de AJAX -->
<?php include "./app/views/inc/script.php"; ?>