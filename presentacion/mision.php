<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1. MISIÓN</title>
    <style>
        /* Estilos para la sección de Misión */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f3f4f7;
            margin: 0;
            padding: 0;
        }

        /* Contenedor para la sección de Misión */
        .mision-container {
            width: 80%;
            max-width: 1200px; /* Limita el ancho máximo para pantallas grandes */
            margin: 20px auto; /* Espaciado arriba y abajo */
            text-align: left;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border-radius: 8px; /* Esquinas redondeadas */
        }

        .mision-header {
            background-color: #0099cc;
            color: white;
            padding: 15px;
            font-size: 2em; /* Aumenta el tamaño del encabezado */
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
            border-radius: 8px; /* Esquinas redondeadas */
        }

        .mision-text {
            font-size: 1.2em; /* Tamaño de fuente más grande */
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        ul {
            margin: 20px 0; /* Espaciado para la lista */
            padding-left: 20px; /* Sangría para la lista */
            list-style-type: disc; /* Tipo de lista */
        }

        textarea {
            width: 100%; /* Usar el ancho completo del contenedor */
            height: 100px;
            margin: 20px auto;
            padding: 10px;
            font-size: 1em; /* Tamaño de fuente más pequeño para el textarea */
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            resize: none; /* Evita que el textarea se redimensione */
        }

        /* Botones de navegación en la parte inferior */
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 600px; /* Limita el ancho máximo de los botones */
            margin: 20px auto;
        }

        button.nav-button, button.save-button {
            background-color: #0099cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em; /* Tamaño de fuente más pequeño para los botones */
            transition: 0.3s;
            flex: 1; /* Los botones ocuparán el mismo espacio */
            margin: 0 5px; /* Espaciado entre botones */
        }

        button.nav-button:hover, button.save-button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        function guardarMision() {
            const mision = document.getElementById('mision-textarea').value;
            if (mision) {
                // Simulando guardar la misión (esto podría ser una llamada a la API o almacenamiento local)
                localStorage.setItem('mision', mision);
                alert('La misión ha sido guardada con éxito.');
            } else {
                alert('Por favor, escriba la misión antes de guardar.');
            }
        }
    </script>
</head>
<body>

    <div class="mision-container">
        <h2 class="mision-header">1. MISIÓN</h2>
        <p class="mision-text">
            La <strong>MISIÓN</strong> es la razón de ser de la empresa/organización.
        </p>
        <ul>
            <li>Debe ser clara, concisa y compartida.</li>
            <li>Siempre orientada hacia el cliente, no hacia el producto o servicio.</li>
            <li>Refleja el propósito fundamental de la empresa en el mercado.</li>
        </ul>
        <p class="mision-text">
            En términos generales, describe la actividad y razón de ser de la organización y contribuye como una referencia permanente en el proceso de planificación estratégica. 
            Se expresa a través de una oración que define el propósito fundamental de su empresa.
        </p>

        <div class="mision-examples">
            <strong>EJEMPLOS</strong><br>
            <strong>Empresa de servicios</strong>
            <p>La gestión de servicios que contribuyen a la calidad de vida de las personas y generan valor para los grupos de interés.</p>
            <strong>Empresa productora de café</strong>
            <p>Gracias a nuestro entusiasmo, trabajo en equipo y valores, queremos deleitar a todos aquellos que, en el mundo, aman la calidad de vida a través del mejor café.</p>
            <strong>Agencia de certificación</strong>
            <p>Dar a nuestros clientes alivio económico a través de la gestión de la Calidad, la Seguridad y la Responsabilidad Social.</p>
        </div>

        <!-- Área de entrada de misión -->
        <textarea id="mision-textarea" placeholder="En este apartado describa la Misión de su empresa."></textarea>
        <button class="save-button" onclick="guardarMision()">Guardar Misión</button>
    </div>

    <!-- Botones de navegación en la parte inferior -->
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php'">ÍNDICE</button>
        <button class="nav-button" onclick="window.location.href='vision.php'">2. VISIÓN</button>
    </div>

</body>
</html>
