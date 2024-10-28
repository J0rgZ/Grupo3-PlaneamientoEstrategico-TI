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

// Inicializar la variable de visión
$vision_usuario = "";
$plan_id = $_GET['plan_id'] ?? '';

// Validar el formato del plan_id
if (!preg_match('/^[0-9a-f]{24}$/', $plan_id)) {
    header('Location: inicio.php?error=InvalidID');
    exit();
}

// Recuperar la visión existente del plan desde MongoDB
try {
    $collection = $db->planes;

    $documento = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id), 'user_id' => $_SESSION['user_id']]);

    if ($documento && isset($documento['vision'])) {
        $vision_usuario = $documento['vision'];
    }
} catch (Exception $e) {
    error_log("Error al recuperar visión: " . $e->getMessage());
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_vision = $_POST['vision'] ?? '';

    if ($nueva_vision) {
        try {
            $result = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
                ['$set' => [
                    'vision' => $nueva_vision,
                    'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
                ]]
            );

            if ($result->getModifiedCount() > 0) {
                $_SESSION['success_message'] = "Visión actualizada exitosamente.";
            } else {
                $_SESSION['error_message'] = "No se realizaron cambios en la visión.";
            }
        } catch (Exception $e) {
            error_log("Error al actualizar visión: " . $e->getMessage());
            $_SESSION['error_message'] = "Ocurrió un error al actualizar la visión. Por favor, intenta nuevamente.";
        }

        header("Location: vision.php?plan_id=$plan_id");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>2. VISIÓN</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        /* Barra de progreso en pasos */
        .progress-container {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
        }

        .progress-step {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .step {
            width: 30px;
            height: 30px;
            background-color: #d1d1d1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .step.completed {
            background-color: #0099cc;
        }

        .step-line {
            flex: 1;
            height: 4px;
            background-color: #d1d1d1;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .step-line.active {
            background-color: #0099cc;
        }

        /* Contenedor de contenido */
        .content-container {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px auto;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .content-header {
            background-color: #0099cc;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 20px;
        }

        .content-text {
            font-size: 1.1em;
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Área de texto mejorada */
        textarea {
            width: 95%;
            height: 140px;
            margin-top: 15px;
            padding: 15px;
            font-size: 1em;
            border-radius: 8px;
            border: 2px solid #ddd;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            resize: none;
            transition: all 0.3s ease;
        }

        textarea:focus {
            border-color: #0099cc;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 153, 204, 0.2);
            outline: none;
        }

        /* Botones de navegación */
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
        }

        button {
            background-color: #0099cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
        }

        .success-message, .error-message {
            padding: 15px;
            margin: 20px auto;
            border-radius: 8px;
            width: 90%;
            max-width: 1000px; 
            text-align: center;
            font-size: 1.2em;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Efecto de animación */
        .success-message.show, .error-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .success-message.hide, .error-message.hide {
            opacity: 0;
            transform: translateY(-10px);
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
        }

        button {
            background-color: #0099cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        button i {
            margin-right: 8px; /* Espacio entre el ícono y el texto */
        }

        button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

    <!-- Barra de progreso en pasos -->
    <div class="progress-container">
        <div class="progress-step">
            <div class="step completed">1</div>
            <div class="step-line active"></div>
            <div class="step completed">2</div>
            <div class="step-line active"></div>
            <div class="step">3</div>
            <div class="step-line"></div>
            <div class="step">4</div>
            <div class="step-line"></div>
            <div class="step">5</div>
            <div class="step-line"></div>
            <div class="step">6</div>
            <div class="step-line"></div>
            <div class="step">7</div>
            <div class="step-line"></div>
            <div class="step">8</div>
        </div>
    </div>

    <div class="content-container">
        <div class="content-header">2. VISIÓN</div>
        <p class="content-text">
            La <strong>VISIÓN</strong> es lo que la empresa/organización aspira lograr en el futuro.
        </p>
        <ul>
            <li>Debe ser clara, positiva y compartida.</li>
            <li>Proyecta la imagen de destino que se pretende alcanzar.</li>
            <li>Refleja las aspiraciones a largo plazo.</li>
        </ul>

        <?php
        function mostrarMensaje($tipo, $mensaje) {
            echo "<div class='{$tipo}-message'>" . htmlspecialchars($mensaje) . "</div>";
        }

        // En la sección de mensajes
        if (isset($_SESSION['success_message'])) {
            mostrarMensaje('success', $_SESSION['success_message']);
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            mostrarMensaje('error', $_SESSION['error_message']);
            unset($_SESSION['error_message']);
        }

        ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <textarea name="vision" id="vision-textarea" placeholder="Describe la Visión de tu empresa." required><?php echo htmlspecialchars($vision_usuario); ?></textarea>
            <button class="save-button" type="submit">Guardar Visión</button>
        </form>

    </div>

    <!-- Botones de navegación en la parte inferior -->
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-home"></i> INDICE
        </button>
        <button class="nav-button" onclick="window.location.href='mision.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-bullseye"></i> 1. MISION
        </button>
        <button class="nav-button" onclick="window.location.href='valores.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-hand-holding-heart icon"></i> 3. VALORES
        </button>
    </div>

</body>
</html>


