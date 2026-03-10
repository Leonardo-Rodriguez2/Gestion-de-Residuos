<?php
    use app\controllers\productController;
    use app\models\mainModel;

    // Instancia de los controladores/modelos que se necesitan
    $insProducto = new productController();
    $db = new mainModel(); 
    
    // Se elimina la lógica de FPDF del lado del servidor.
?>

<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de productos</h2>
</div>
<div class="container pb-6 pt-6">
    
<?php
    // 1. Verificar si NO hay un término de búsqueda activo
    if(!isset($_SESSION[$url[0]]) || empty($_SESSION[$url[0]])){
        
        // BOTÓN DE REPORTE GENERAL (Ahora activa una función JS)
        echo '
        <div class="columns">
            <div class="column">
                <button id="btn-reporte-general" onclick="generarReporteGeneral()" class="button is-link is-rounded mb-6">
                    <i class="fas fa-file-pdf fa-fw"></i> &nbsp; Generar Reporte General (PDF)
                </button>
            </div>
        </div>
        ';
        // Fin Botón Reporte General

        // Si NO hay término de búsqueda, mostrar el formulario de búsqueda
?>

<?php
        // 🚨 CONTENEDOR PARA EL PDF GENERAL 🚨
        // Este div contendrá todo el listado de productos para que html2canvas lo capture.
        echo '<div id="listado_productos_pdf_general" class="listado_productos_pdf">';
        echo $insProducto->listarProductoControlador($url[1],10,$url[0],"",0);
        echo '</div>'; // Cierra el contenedor de captura
        
    }else{ 
        // 2. Si SI hay un término de búsqueda activo
?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6 FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <p><i class="fas fa-search fa-fw"></i> &nbsp; Estas buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded"><i class="fas fa-trash-restore"></i> &nbsp; Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
        // 🚨 CONTENEDOR PARA EL PDF GENERAL (FILTRADO) 🚨
        echo '<div id="listado_productos_pdf_general" class="listado_productos_pdf">';
        echo $insProducto->listarProductoControlador($url[1],10,$url[0],$_SESSION[$url[0]],0);
        echo '</div>'; // Cierra el contenedor de captura
        }
    ?>
</div>

<div class="form-rest mb-6 mt-6"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    // ⚠️ FUNCIÓN PARA DEPURACIÓN ⚠️
    // Verifica si la librería jsPDF se cargó correctamente al iniciar la página.
    window.onload = function() {
        if (typeof window.jspdf === 'undefined') {
            console.error("DEBUG-PDF: ERROR CRÍTICO. La librería jspdf no se cargó.");
            alert("Error de Carga: La librería jsPDF necesaria para el reporte NO se cargó. Revisa tu conexión y las rutas CDN en la vista.");
        } else {
            console.log("DEBUG-PDF: Librería jsPDF cargada correctamente. Las funciones de reporte están disponibles.");
        }
    };
    
    const { jsPDF } = window.jspdf;
    
    // Función de utilidad para agregar una línea formateada en el reporte individual
    function addLine(doc, label, value, y, fontStyle = 'normal') {
        value = value || 'N/A';
        doc.setFont('helvetica', 'bold');
        doc.text(label, 20, y);
        doc.setFont('helvetica', fontStyle);
        doc.text(value.toString(), 70, y);
    }

    // 2. FUNCIÓN PARA EL REPORTE GENERAL (Captura la lista de artículos)
    function generarReporteGeneral() {
        const element = document.getElementById('listado_productos_pdf_general'); 
        const boton = document.getElementById('btn-reporte-general');

        if (!element) {
            console.error('DEBUG-PDF: No se encontró el elemento con ID "listado_productos_pdf_general".');
            alert('Error: No se encontró el listado de productos para generar el PDF. Revisa la consola (F12).');
            return;
        }

        boton.classList.add('is-loading'); 
        
        html2canvas(element, { 
            scale: 2, // Mejor calidad
            logging: true // Para depuración en consola
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            // Usamos 'p' (portrait) para este formato de lista larga
            const pdf = new jsPDF('p', 'mm', 'a4'); 
            
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = pdf.internal.pageSize.getHeight();

            const imgWidth = pdfWidth - 20; // Ancho con margen
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 15; // Margen superior

            // Título
            pdf.setFontSize(16);
            pdf.text('REPORTE GENERAL DE PRODUCTOS', pdfWidth / 2, 10, null, null, 'center');
            
            // Lógica para manejar múltiples páginas
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= (pdfHeight - position); 

            while (heightLeft > 0) {
                position = heightLeft - imgHeight; 
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 10, position + 10, imgWidth, imgHeight); 
                heightLeft -= pdfHeight;
            }

            boton.classList.remove('is-loading'); 
            pdf.save('Inventario_General.pdf');
        }).catch(error => {
            boton.classList.remove('is-loading'); 
            console.error("DEBUG-PDF: Error al generar el reporte general:", error);
            alert("Ocurrió un error al procesar el reporte general. Revisa la consola (F12).");
        });
    }

    // 3. FUNCIÓN PARA EL REPORTE INDIVIDUAL (Genera un PDF con texto simple)
    function generarReporteIndividual(id, codigo, nombre, precio, stock, categoria) {
        if (typeof jsPDF === 'undefined') {
            alert("Error: El generador de PDF no está activo.");
            return;
        }

        const doc = new jsPDF('p', 'mm', 'a4');
        let y = 30;
        const lineSpacing = 8;
        
        // Formato de moneda (se usará el local del navegador para el ejemplo)
        const precio_formato = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 2 }).format(precio); 

        // Título
        doc.setFontSize(18);
        doc.setFont('helvetica', 'bold');
        doc.text("FICHA TÉCNICA DE PRODUCTO", 105, 15, null, null, 'center');
        doc.setLineWidth(0.5);
        doc.line(20, 18, 190, 18); 

        doc.setFontSize(12);
        
        // Campos
        addLine(doc, "ID Producto:", id, y); y += lineSpacing;
        addLine(doc, "Código:", codigo, y); y += lineSpacing;
        addLine(doc, "Nombre:", nombre, y); y += lineSpacing;
        addLine(doc, "Categoría:", categoria, y); y += lineSpacing;
        doc.setLineWidth(0.1);
        doc.line(20, y - 2, 190, y - 2); 
        y += lineSpacing;
        addLine(doc, "Stock Total:", stock, y); y += lineSpacing;
        addLine(doc, "Precio Venta:", precio_formato, y, 'bold');
        
        doc.save(`Ficha_Producto_${codigo}.pdf`);
    }
</script>