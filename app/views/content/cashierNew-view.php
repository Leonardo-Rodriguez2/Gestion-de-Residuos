<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* --- CSS Personalizado para Compactar y Mejorar la Estética --- */
        
        /* Contenedor principal que centra la tarjeta (sin afectar el body) */
        .page-wrapper {
            display: flex;
            justify-content: center;
            padding: 30px 0;
            /* Usamos este div para centrar la tarjeta */
        }

        .compact-form-card {
            background-color: #ffffff;
            border-radius: 8px; /* Borde suave */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Sombra limpia */
            max-width: 750px; /* Ancho cómodo pero no tan grande */
            width: 100%;
            padding: 30px; /* Espaciado interno adecuado */
        }

        /* Títulos */
        .header-section {
            padding: 10px 0 20px 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db; /* Línea de acento (info/link color) */
            text-align: center;
        }
        .header-section .title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.25rem !important;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Espacio entre icono y texto */
        }
        .header-section .subtitle {
            color: #7f8c8d; /* Gris para subtítulo */
            font-weight: 500;
            font-size: 1rem;
        }

        /* Etiquetas y controles */
        .control label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px; /* Menos espacio, más compacto */
            display: block;
            font-size: 0.9rem;
        }

        /* Inputs (Manteniendo el tamaño estándar 'input') */
        .input {
            border-radius: 4px;
        }
        .input:focus {
            border-color: #3498db !important;
            box-shadow: 0 0 0 0.125em rgba(52, 152, 219, 0.25);
        }

        /* Columnas para más espaciado entre filas */
        .columns {
            margin-bottom: 15px !important; 
        }

        /* Botones */
        .buttons-group {
            margin-top: 30px; 
            padding-top: 15px;
            border-top: 1px dashed #e0e0e0;
        }
        
        /* Campo obligatorio */
        .required-text {
            color: #e74c3c;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="container is-fluid mb-6">
    <div class="header-section">
        <h1 class="title is-4 subtitle"><i class="fas fa-cash-register fa-fw"></i> &nbsp; Nueva caja</h1>
    </div>
</div>

<div class="page-wrapper">
    <div class="compact-form-card">

        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/cajaAjax.php" method="POST" autocomplete="off" >

            <input type="hidden" name="modulo_caja" value="registrar">

            <div class="columns is-mobile is-multiline">
                <div class="column">
                    <div class="control">
                        <label>Numero de caja <span class="required-text"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="text" name="caja_numero" pattern="[0-9]{1,5}" maxlength="5" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Nombre o código de caja <span class="required-text"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input" type="text" name="caja_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ:# ]{3,70}" maxlength="70" required >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Efectivo en caja (<?php echo MONEDA_SIMBOLO; ?>) <span class="required-text"><?php echo CAMPO_OBLIGATORIO; ?></span></label>
                        <input class="input has-text-right" type="text" name="caja_efectivo" pattern="[0-9.]{1,25}" maxlength="25" value="0.00" required >
                    </div>
                </div>
            </div>

            <p class="has-text-centered pt-4 has-text-grey-light">
                <small>Los campos marcados con <span class="required-text"><?php echo CAMPO_OBLIGATORIO; ?></span> son obligatorios</small>
            </p>

            <div class="buttons-group has-text-centered">
                <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
            </div>
            
        </form>
    </div>
</div>