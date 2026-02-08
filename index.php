<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imágenes - Premium Uploader</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1; /* Indigo 500 */
            --primary-hover: #4f46e5; /* Indigo 600 */
            --bg-color: #0f172a; /* Slate 900 */
            --card-bg: #1e293b; /* Slate 800 */
            --text-color: #f8fafc; /* Slate 50 */
            --text-secondary: #94a3b8; /* Slate 400 */
            --border-color: #334155; /* Slate 700 */
            --success-color: #10b981; /* Emerald 500 */
            --error-color: #ef4444; /* Red 500 */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, #1e1b4b, transparent 40%),
                              radial-gradient(circle at bottom left, #312e81, transparent 40%);
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.subtitle {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 1rem;
            padding: 3rem 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: rgba(15, 23, 42, 0.3);
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: var(--primary-color);
            background-color: rgba(99, 102, 241, 0.1);
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .upload-area:hover .upload-icon {
            color: var(--primary-color);
        }

        .upload-text {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .upload-text span {
            color: var(--primary-color);
            text-decoration: underline;
        }

        input[type="file"] {
            display: none;
        }

        .preview-container {
            margin-top: 2rem;
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .result-container {
            background-color: rgba(15, 23, 42, 0.5);
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .url-input {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            outline: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .copy-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            white-space: nowrap;
        }

        .copy-btn:hover {
            background-color: var(--primary-hover);
        }

        .copy-btn:active {
            transform: scale(0.95);
        }

        .copy-btn.copied {
            background-color: var(--success-color);
        }

        .loader {
            display: none;
            width: 48px;
            height: 48px;
            border: 5px solid var(--text-secondary);
            border-bottom-color: var(--primary-color);
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
            margin-top: 1rem;
        }

        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-container {
            display: none;
            flex-direction: column;
            align-items: center;
            margin-top: 2rem;
        }
        
        .error-message {
            color: var(--error-color);
            margin-top: 1rem;
            font-size: 0.9rem;
            display: none;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Sube tu Imagen</h1>
        <p class="subtitle">Formatos soportados: JPG, PNG, GIF, WebP</p>

        <div class="upload-area" id="drop-zone">
            <div class="upload-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </div>
            <p class="upload-text">Arrastra y suelta tu imagen aquí o <span>haz clic para buscar</span></p>
            <input type="file" id="file-input" accept="image/*">
        </div>

        <div class="loading-container" id="loader-container">
            <span class="loader" id="loader"></span>
            <p style="margin-top: 0.5rem; color: var(--text-secondary);">Subiendo...</p>
        </div>

        <div class="error-message" id="error-message"></div>

        <div class="preview-container" id="preview-container">
            <img src="" alt="Vista previa" class="preview-image" id="preview-image">
            
            <div class="result-container">
                <input type="text" class="url-input" id="url-input" readonly>
                <button class="copy-btn" id="copy-btn">Copiar</button>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const urlInput = document.getElementById('url-input');
        const copyBtn = document.getElementById('copy-btn');
        const loaderContainer = document.getElementById('loader-container');
        const loader = document.getElementById('loader');
        const errorMessage = document.getElementById('error-message');

        // Funcionalidad de arrastrar y soltar
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // Clic para subir
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                handleFile(fileInput.files[0]);
            }
        });

        function handleFile(file) {
            // Reiniciar estado
            errorMessage.style.display = 'none';
            errorMessage.textContent = '';
            previewContainer.style.display = 'none';
            loader.style.display = 'inline-block';
            loaderContainer.style.display = 'flex';
            dropZone.style.display = 'none';

            // Validar tipo de archivo
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showError('Tipo de archivo no válido. Solo se permiten imágenes JPG, PNG, GIF y WebP.');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loader.style.display = 'none';
                loaderContainer.style.display = 'none';

                if (data.success) {
                    showSuccess(data.url);
                } else {
                    showError(data.message || 'Error al subir la imagen.');
                    dropZone.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // loader.style.display = 'none'; // Error: loader is not defined in this scope if the previous block failed? No, it is defined globally.
                loaderContainer.style.display = 'none';
                showError('Error de conexión o del servidor.');
                dropZone.style.display = 'block';
            });
        }

        function showSuccess(url) {
            previewImage.src = url;
            urlInput.value = url;
            previewContainer.style.display = 'block';
            // Opción para subir otra imagen (recargar página o botón "Subir otra")
            // Por ahora, mostrar la vista previa y ocultar la zona de carga es suficiente.
        }

        function showError(msg) {
            loaderContainer.style.display = 'none';
            errorMessage.textContent = msg;
            errorMessage.style.display = 'block';
        }

        // Botón Copiar
        copyBtn.addEventListener('click', () => {
            urlInput.select();
            urlInput.setSelectionRange(0, 99999); // Para móviles

            navigator.clipboard.writeText(urlInput.value).then(() => {
                const originalText = copyBtn.textContent;
                copyBtn.textContent = '¡Copiado!';
                copyBtn.classList.add('copied');

                setTimeout(() => {
                    copyBtn.textContent = originalText;
                    copyBtn.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Error al copiar: ', err);
            });
        });
    </script>
</body>
</html>