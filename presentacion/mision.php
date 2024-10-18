<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inicializar la variable de misión
$mision_usuario = "";

// Recuperar la misión existente del usuario desde MongoDB
try {
    $collection = $db->mision;

    // Buscar un documento donde 'user_id' coincida con el ID del usuario logueado
    $documento = $collection->findOne(['user_id' => $_SESSION['user_id']]);

    if ($documento && isset($documento['mision'])) {
        // Asignar la misión existente a la variable
        $mision_usuario = $documento['mision'];
    }
} catch (Exception $e) {
    // Manejar errores de conexión o consulta
    error_log("Error al recuperar misión: " . $e->getMessage());
    // Establecer un mensaje de error para el usuario
    $_SESSION['error_message'] = "Ocurrió un error al recuperar tu misión. Por favor, intenta nuevamente.";
}
?>
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
            max-width: 1200px;
            margin: 20px auto;
            text-align: left;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border-radius: 8px;
        }

        .mision-header {
            background-color: #0099cc;
            color: white;
            padding: 15px;
            font-size: 2em;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 1px;
            border-radius: 8px;
        }

        .mision-text {
            font-size: 1.2em;
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        ul {
            margin: 20px 0;
            padding-left: 20px;
            list-style-type: disc;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin: 20px auto;
            padding: 10px;
            font-size: 1em;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            resize: none;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
        }

        button.nav-button, button.save-button {
            background-color: #0099cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
            flex: 1;
            margin: 0 5px;
        }

        button.nav-button:hover, button.save-button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
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

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                    echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php
                    echo htmlspecialchars($_SESSION['error_message']);
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario que envía datos a logicaMision.php -->
        <form method="POST" action="../logica/logicaMision.php">
            <!-- Token CSRF oculto -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <!-- Área de texto para ingresar la misión, prellenada con la misión existente -->
            <textarea name="mision" id="mision-textarea" placeholder="En este apartado describa la Misión de su empresa." required><?php echo htmlspecialchars($mision_usuario); ?></textarea>
            <button class="save-button" type="submit">Guardar Misión</button>
        </form>
    </div>

    <!-- Botones de navegación en la parte inferior -->
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php'">ÍNDICE</button>
        <button class="nav-button" onclick="window.location.href='vision.php'">2. VISIÓN</button>
    </div>

</body>
</html>
