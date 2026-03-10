<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos generales para centrar el contenido y darle un fondo limpio */
        .page-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }
        .data-list-card {
            background-color: #ffffff;
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 900px; /* Ancho para la tabla */
            width: 100%;
            padding: 30px;
        }

        /* Títulos */
        .header-section {
            padding: 10px 0 20px 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db; 
        }
        .header-section .title {
            color: #2c3e50; 
            font-weight: 700;
        }
        .header-section .subtitle {
            color: #7f8c8d;
            font-size: 1rem;
        }

        /* Tabla */
        .table-container {
            overflow-x: auto;
        }
        .table {
            min-width: 700px; /* Asegura un ancho mínimo para mejor visualización */
        }
        .table thead th {
            font-weight: 700;
            background-color: #f5f5f5; /* Fondo más claro para encabezados */
            color: #34495e;
            border-color: #dbdbdb;
        }
        .table tbody tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="container is-fluid mb-6">
    <div class="header-section">
        <h1 class="title is-4 subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de cajas</h1>
    </div>
</div>

<div class="page-wrapper">
    <div class="data-list-card">
        
        <div class="form-rest mb-6 mt-6"></div>

        <?php
            use app\controllers\cashierController;

            // Instancia el controlador
            $insCaja = new cashierController();

            // Llama al controlador para listar la tabla
            echo $insCaja->listarCajaControlador($url[1],15,$url[0],"");
        ?>
    </div>
</div>