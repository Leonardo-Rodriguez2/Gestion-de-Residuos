<?php
// PHP vacío ya que toda la lógica se maneja en JavaScript y localStorage.
?>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Control de Moneda</title>
</head>

<body>
    <style>

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5; /* Fondo ligero */
        }

        .compact-card {
            width: 100%;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra para destacar */
            background-color: white;
            border-radius: 8px;
        }

        .title-group {
            border-bottom: 2px solid #3498db; 
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .title {
            color: #34495e;
            font-weight: 700;
            margin-bottom: 0 !important;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 1rem;
            margin-top: 5px;
        }

        .rate-display {
            text-align: center;
            padding: 15px;
            margin-bottom: 25px;
            background-color: #ecf0f1; 
            border-radius: 6px;
            min-height: 10rem; /* Mantener la altura uniforme */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .rate-display .value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2ecc71; 
        }
        .rate-display .date {
            font-size: 0.8rem;
            color: #95a5a6;
            margin-top: 5px;
            display: block;
        }

        .field-group {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .field-group .field {
            flex: 1;
        }
        
        .input, .button {
            border-radius: 6px; 
        }
        
        .btn-bcv {
            background-color: #3498db; 
            border-color: #3498db;
        }
        .btn-bcv:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .btn-guardar {
            background-color: #2ecc71; 
            border-color: #2ecc71;
        }
        .btn-guardar:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        
        .rate-modified {
            color: #e67e22 !important; 
        }
        
        /* Estilos para el encabezado del modal de alerta/confirmación */
        .modal-card-head {
            background-color: #3498db;
            color: white;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }
        .modal-card-title {
            color: white;
            font-weight: 600;
        }
        .modal-card-head .delete {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="compact-card">
        <div class="title-group">
            <h1 class="title is-4"><i class="fas fa-money-check-alt fa-fw"></i> Control de Moneda</h1>
        </div>

        <div class="rate-display">
            Tasa Guardada: 1 USD = 
            <span id="valor-bcv" class="value">0.00</span> Bs.
            <span id="fecha-actualizacion" class="date">
                Última Actualización: <span id="fecha-localstorage-span">N/A</span>
            </span>
            <span id="indicador-offline" class="date" style="color: red; font-weight: bold; display: none;">
                ⚠️ Usando Tasa Sin Conexión
            </span>
        </div>

        <div class="field-group">
            <div class="field">
                <label class="label is-small">Dólar ($)</label>
                <div class="control">
                    <input class="input is-medium has-text-right" type="number" id="input-usd" value="1.00" min="0.01" step="0.01">
                </div>
            </div>
            
            <div class="field">
                <label class="label is-small">Bolívares (Bs.)</label>
                <div class="control">
                    <input class="input is-medium has-text-right" type="number" id="output-bs" value="0.00" step="0.01"> 
                </div>
            </div>
        </div>
        
        <div class="buttons is-centered mt-4">
            <button class="button is-danger btn-bcv" id="btn-reset" disabled>
                <span class="icon"><i class="fas fa-times-circle"></i></span>
                <span>Restaurar BCV</span>
            </button>
            <button class="button is-info btn-bcv" id="btn-bcv">
                <span class="icon"><i class="fas fa-sync-alt"></i></span>
                <span>Actualizar BCV</span>
            </button>
            <button class="button is-success btn-guardar" id="btn-guardar">
                <span class="icon"><i class="far fa-save"></i></span>
                <span>Guardar Tasa</span>
            </button>
        </div>

    </div>

    <div class="modal" id="custom-modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title" id="modal-title"></p>
                <button class="delete" aria-label="close" id="modal-close-button"></button>
            </header>
            <section class="modal-card-body" id="modal-body">
                </section>
            <footer class="modal-card-foot" id="modal-footer">
                </footer>
        </div>
    </div>

    <script>
        const API_URL = "https://ve.dolarapi.com/v1/dolares/oficial";
        const LOCAL_STORAGE_KEY_RATE = 'dolar_rate';
        const LOCAL_STORAGE_KEY_DATE = 'dolar_last_update';
        const LOCAL_STORAGE_KEY_SOURCE = 'dolar_source';
        const UPDATE_INTERVAL_MS = 6 * 60 * 60 * 1000;

        const inputUsd = document.getElementById('input-usd');
        const outputBs = document.getElementById('output-bs');
        const valorBcvSpan = document.getElementById('valor-bcv');
        const fechaStorageSpan = document.getElementById('fecha-localstorage-span');
        const indicadorOffline = document.getElementById('indicador-offline');
        const btnBcv = document.getElementById('btn-bcv');
        const btnGuardar = document.getElementById('btn-guardar');
        const btnReset = document.getElementById('btn-reset');
        
        // Elementos del Modal
        const customModal = document.getElementById('custom-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');
        const modalFooter = document.getElementById('modal-footer');
        const modalCloseButton = document.getElementById('modal-close-button');
        const modalBackground = document.querySelector('.modal-background');

        let currentRate = 0; 
        let modalResolve = null; // Para manejar la promesa del confirm

        // --- Funciones de Modal ---

        // Función genérica para mostrar el modal
        function showModal(title, message, isConfirm = false) {
            return new Promise((resolve) => {
                modalResolve = resolve;
                modalTitle.textContent = title;
                modalBody.innerHTML = `<p>${message}</p>`;
                modalFooter.innerHTML = ''; 

                // Botón Cerrar (para alertas) o Cancelar (para confirmaciones)
                const closeButton = document.createElement('button');
                closeButton.className = 'button';
                closeButton.textContent = isConfirm ? 'Cancelar' : 'Cerrar';
                closeButton.addEventListener('click', () => {
                    customModal.classList.remove('is-active');
                    resolve(false); 
                });
                modalFooter.appendChild(closeButton);

                if (isConfirm) {
                    // Botón Aceptar para confirmaciones
                    const confirmButton = document.createElement('button');
                    confirmButton.className = 'button is-success';
                    confirmButton.textContent = 'Aceptar';
                    confirmButton.addEventListener('click', () => {
                        customModal.classList.remove('is-active');
                        resolve(true); 
                    });
                    modalFooter.appendChild(confirmButton);
                }

                customModal.classList.add('is-active');
            });
        }
        
        // Eventos para cerrar el modal al hacer click en el fondo o en la 'x'
        modalCloseButton.addEventListener('click', () => {
            customModal.classList.remove('is-active');
            // Si hay una promesa de confirm pendiente, la resolvemos como 'false' (Cancelar)
            if (modalResolve) {
                modalResolve(false);
                modalResolve = null;
            }
        });
        modalBackground.addEventListener('click', () => {
            customModal.classList.remove('is-active');
            if (modalResolve) {
                modalResolve(false);
                modalResolve = null;
            }
        });

        // Reemplazo de alert()
        function customAlert(message, title = 'Notificación') {
            return showModal(title, message, false);
        }

        // Reemplazo de confirm()
        function customConfirm(message, title = 'Confirmación') {
            return showModal(title, message, true);
        }

        // --- Funciones de Lógica de la Aplicación ---

        function loadRateFromLocalStorage() {
            const storedRate = localStorage.getItem(LOCAL_STORAGE_KEY_RATE);
            const storedDate = localStorage.getItem(LOCAL_STORAGE_KEY_DATE);
            const storedSource = localStorage.getItem(LOCAL_STORAGE_KEY_SOURCE);

            if (storedRate) {
                currentRate = parseFloat(storedRate);
                valorBcvSpan.textContent = currentRate.toFixed(2);
                fechaStorageSpan.textContent = formatTimestamp(storedDate);
                
                if (storedSource === 'user') {
                    valorBcvSpan.classList.add('rate-modified');
                    fechaStorageSpan.textContent += ' (Usuario)';
                    btnReset.disabled = false;
                } else {
                    valorBcvSpan.classList.remove('rate-modified');
                    btnReset.disabled = true;
                }
            } else {
                currentRate = 0;
                valorBcvSpan.textContent = '0.00';
                fechaStorageSpan.textContent = 'Nunca';
            }
            
            return currentRate;
        }

        function saveRateToLocalStorage(rate, source = 'bcv') {
            const now = Date.now();
            localStorage.setItem(LOCAL_STORAGE_KEY_RATE, rate.toFixed(4));
            localStorage.setItem(LOCAL_STORAGE_KEY_DATE, now);
            localStorage.setItem(LOCAL_STORAGE_KEY_SOURCE, source);
            loadRateFromLocalStorage();
        }

        function formatTimestamp(timestamp) {
            if (!timestamp) return 'N/A';
            const date = new Date(parseInt(timestamp));
            const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('es-VE', options);
        }

        function calculateConversion(source) {
            const usdValue = parseFloat(inputUsd.value);
            const bsValue = parseFloat(outputBs.value);
            
            if (currentRate === 0) return;

            if (source === 'usd') {
                if (isNaN(usdValue) || usdValue < 0) { outputBs.value = '0.00'; return; }
                const newBsValue = usdValue * currentRate;
                outputBs.value = newBsValue.toFixed(2); 

            } else if (source === 'bs') {
                if (isNaN(bsValue) || bsValue < 0) { inputUsd.value = '0.00'; return; }
                const newUsdValue = bsValue / currentRate;
                inputUsd.value = newUsdValue.toFixed(2); 
            }
        }
        
        async function fetchAndSaveBcvRate() {
            btnBcv.classList.add('is-loading');
            indicadorOffline.style.display = 'none';
            let message = '';
            let title = 'Actualización BCV';

            try {
                if (!navigator.onLine) {
                    throw new Error("Sin conexión a Internet.");
                }

                const response = await fetch(API_URL);
                if (!response.ok) throw new Error("Error al consultar la API.");
                
                const data = await response.json();
                const officialRate = parseFloat(data.promedio); 

                if (isNaN(officialRate) || officialRate <= 0) throw new Error("Valor BCV inválido.");

                saveRateToLocalStorage(officialRate, 'bcv');
                currentRate = officialRate; 
                message = `Tasa BCV (**${officialRate.toFixed(2)} Bs.**) actualizada y guardada exitosamente.`;

            } catch (error) {
                console.warn("Error de conexión/API:", error.message);
                indicadorOffline.style.display = 'block';
                title = 'Error de Conexión';
                message = `No se pudo obtener la tasa BCV. Usando el valor guardado localmente (**${currentRate.toFixed(2)} Bs.**).`;
            } finally {
                btnBcv.classList.remove('is-loading');
                calculateConversion('usd'); 
                await customAlert(message, title);
            }
        }

        function shouldUpdateBcv() {
            const lastUpdate = localStorage.getItem(LOCAL_STORAGE_KEY_DATE);
            const source = localStorage.getItem(LOCAL_STORAGE_KEY_SOURCE);
            
            if (!lastUpdate || source === 'user') return true; 

            const timeElapsed = Date.now() - parseInt(lastUpdate);
            return timeElapsed >= UPDATE_INTERVAL_MS;
        }

        btnGuardar.addEventListener('click', async function() {
            // Se toma el valor actual del campo Bolívares.
            const bsValueToSet = parseFloat(outputBs.value);
            
            if (!isNaN(bsValueToSet) && bsValueToSet > 0) {
                
                const confirmed = await customConfirm(`¿Seguro que desea guardar **${bsValueToSet.toFixed(2)} Bs.** como la nueva tasa oficial (1 USD)? Esto anulará el valor BCV.`, 'Guardar Tasa Personalizada');
                
                if (confirmed) {
                    // Se establece 1.00 en USD (para que la conversión sea 1:X)
                    inputUsd.value = '1.00'; 
                    currentRate = bsValueToSet;
                    
                    // Se guarda el nuevo valor
                    saveRateToLocalStorage(currentRate, 'user');
                    await customAlert(`Nueva tasa (**${currentRate.toFixed(2)} Bs.**) guardada por el usuario.`, 'Tasa Guardada');
                }
            } else {
                await customAlert('El valor de Bolívares (Bs.) debe ser positivo para guardar una nueva tasa.', 'Error al Guardar');
            }
        });
        
        btnReset.addEventListener('click', async function() {
             const confirmed = await customConfirm('¿Desea restaurar la tasa oficial a la última obtenida del BCV y eliminar la modificación del usuario?', 'Restaurar Tasa');
             if (confirmed) {
                localStorage.removeItem(LOCAL_STORAGE_KEY_SOURCE);
                fetchAndSaveBcvRate();
            }
        });

        inputUsd.addEventListener('input', () => calculateConversion('usd'));
        outputBs.addEventListener('input', () => calculateConversion('bs'));
        
        btnBcv.addEventListener('click', fetchAndSaveBcvRate);

        window.onload = function() {
            loadRateFromLocalStorage();
            
            if (shouldUpdateBcv()) {
                fetchAndSaveBcvRate();
            } else {
                calculateConversion('usd'); 
            }
        };

    </script>
</body>