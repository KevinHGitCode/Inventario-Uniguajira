function toggleExcelUploadUI() {
    const excelUploadUI = document.getElementById('excel-upload-content');
    const goodsContent = document.getElementById('bienes-grid');

    // Toggle class hidden
    excelUploadUI.classList.toggle('hidden');
    goodsContent.classList.toggle('hidden');
}

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Check file type
    const validTypes = ['.xlsx', '.xls', '.csv'];
    const fileType = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
    if (!validTypes.includes(fileType)) {
        alert('Por favor seleccione un archivo Excel válido (.xlsx, .xls, .csv)');
        return;
    }

    // Process the file
    loadDataFromExcel(file);
}

/**
 * Loads and processes data from an Excel file
 * @param {File} file - The Excel file to be processed
 * @description This function reads an Excel file and converts it to JSON format.
 * The reader.readAsBinaryString(file) method reads the contents of the file as a binary string,
 * which is then used by the XLSX library to parse the Excel data.
 * Once loaded, it accesses the first sheet and converts it to a JSON array.
 */
function loadDataFromExcel(file) {
    console.log('Iniciando carga del archivo Excel:', file.name);

    const reader = new FileReader();
    reader.onload = function(event) {
        console.log('Archivo leído correctamente, procesando datos...');
        const data = event.target.result;
        const workbook = XLSX.read(data, { type: 'binary' });
        const firstSheetName = workbook.SheetNames[0];
        console.log('Primera hoja encontrada:', firstSheetName);

        const worksheet = workbook.Sheets[firstSheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        console.log('Datos convertidos a JSON:', jsonData);

        // Verificar encabezados requeridos
        const headers = jsonData[0];
        console.log('Encabezados encontrados:', headers);
        if (!headers || !headers.some(h => h.toLowerCase() === 'bien') || !headers.some(h => h.toLowerCase() === 'tipo')) {
            console.error('Encabezados requeridos no encontrados. Se requiere "bien" y "tipo".');
            alert('El archivo Excel debe contener los encabezados "bien" y "tipo" (no sensible a mayúsculas).');
            return;
        }

        const bienIndex = headers.findIndex(h => h.toLowerCase() === 'bien');
        const tipoIndex = headers.findIndex(h => h.toLowerCase() === 'tipo');
        const imagenIndex = headers.findIndex(h => h.toLowerCase() === 'imagen');

        // Obtener bienes existentes para validar duplicados
        const existingGoods = window.globalAutocomplete.getItems().map(item => item.bien.toLowerCase());

        // Limpiar tabla de previsualización
        const previewBody = document.getElementById('excel-preview-body');
        previewBody.innerHTML = '';
        console.log('Tabla de previsualización limpiada.');

        // Procesar filas de datos
        jsonData.slice(1).forEach((row, index) => {
            const bien = row[bienIndex]?.trim();
            const tipo = row[tipoIndex]?.trim();
            const imagen = row[imagenIndex]?.trim();

            // Validar que el bien no sea N/A y no esté duplicado
            if (!bien || bien.toLowerCase() === 'n/a' || existingGoods.includes(bien.toLowerCase())) {
                console.warn(`Fila ${index + 1} ignorada: bien inválido o duplicado (${bien}).`);
                return;
            }

            console.log(`Procesando fila ${index + 1}:`, { bien, tipo, imagen });

            // Crear fila de la tabla
            const tr = document.createElement('tr');

            // Columna "Bien"
            const tdBien = document.createElement('td');
            tdBien.textContent = bien;
            tr.appendChild(tdBien);

            // Columna "Tipo"
            const tdTipo = document.createElement('td');
            tdTipo.textContent = tipo || 'N/A';
            tr.appendChild(tdTipo);

            // Columna "Imagen" (espacio para subir)
            const tdImagen = document.createElement('td');
            const imgInput = document.createElement('input');
            imgInput.type = 'file';
            imgInput.accept = 'image/*';
            imgInput.classList.add('image-upload-input');
            tdImagen.appendChild(imgInput);
            tr.appendChild(tdImagen);

            // Agregar fila a la tabla
            previewBody.appendChild(tr);
            console.log('Fila agregada a la tabla:', tr);
        });

        // Mostrar tabla si tiene datos
        const table = document.querySelector('#excel-preview-table table');
        if (previewBody.children.length > 0) {
            table.classList.remove('hidden');
            console.log('Tabla de previsualización mostrada.');
        } else {
            table.classList.add('hidden');
            console.warn('No se encontraron datos válidos para mostrar en la tabla.');
        }

        updateEnviarButtonState();
    };

    reader.onerror = function() {
        console.error('Error al leer el archivo:', reader.error);
        alert('Ocurrió un error al leer el archivo. Intente nuevamente.');
    };

    reader.readAsBinaryString(file);
    console.log('Lectura del archivo iniciada.');
}

function btnClearExcelUploadUI() {
    toggleExcelUploadUI();

    // Limpiar el input de archivo
    const excelFileInput = document.getElementById('excelFileInput');
    excelFileInput.value = '';


    // Limpiar la tabla de previsualización
    const previewBody = document.getElementById('excel-preview-body');
    previewBody.innerHTML = '';

    // Ocultar la tabla de previsualización
    const table = document.querySelector('#excel-preview-table table');
    table.classList.add('hidden');

    updateEnviarButtonState();
}

function sendGoodsData() {
    const goods = collectGoodsData();
    if (!goods.length) {
        showToast({ success: false, message: 'No hay datos para enviar.' });
        return;
    }
    fetch('/api/goods/batchCreate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ goods }),
    })
        .then(response => response.json())
        .then(data => {
            showToast(data);
            if (data.success) {
                loadContent('/goods');
                btnClearExcelUploadUI();
            }
        })
        .catch(error => {
            showToast(error);
        });
}

function mapTipoToEnum(tipo) {
    if (tipo.toLowerCase() === 'cantidad') return 1;
    if (tipo.toLowerCase() === 'serial') return 2;
    return null; // Invalid type
}

function collectGoodsData() {
    const rows = document.querySelectorAll('#excel-preview-body tr');
    const goods = [];

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const bien = cells[0]?.textContent.trim();
        const tipo = cells[1]?.textContent.trim();
        const imagenInput = cells[2]?.querySelector('input[type="file"]');
        const imagen = imagenInput?.files[0]?.name || null;

        const tipoEnum = mapTipoToEnum(tipo);
        if (bien && tipoEnum) {
            goods.push({ nombre: bien, tipo: tipoEnum, imagen });
        }
    });

    return goods;
}


function updateEnviarButtonState() {
    const btn = document.getElementById('btnEnviarExcel');
    btn.disabled = collectGoodsData().length === 0;
}
// Llama a updateEnviarButtonState() cada vez que cambie la previsualización
// Por ejemplo, después de cargar/previsualizar el archivo Excel:
// updateEnviarButtonState();
// Si ya tienes un evento para actualizar la tabla, llama ahí también.