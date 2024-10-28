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
$plan_id = $_GET['plan_id'] ?? '';

// Recuperar la misión existente del plan desde MongoDB
try {
    $collection = $db->planes;

    $documento = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id), 'user_id' => $_SESSION['user_id']]);

    if ($documento && isset($documento['mision'])) {
        $mision_usuario = $documento['mision'];
    }
} catch (Exception $e) {
    error_log("Error al recuperar misión: " . $e->getMessage());
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_mision = $_POST['mision'] ?? '';

    if ($nueva_mision) {
        try {
            $result = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
                ['$set' => [
                    'mision' => $nueva_mision,
                    'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
                ]]
            );

            if ($result->getModifiedCount() > 0) {
                $_SESSION['success_message'] = "Misión actualizada exitosamente.";
            } else {
                $_SESSION['error_message'] = "No se realizaron cambios en la misión.";
            }
        } catch (Exception $e) {
            error_log("Error al actualizar misión: " . $e->getMessage());
            $_SESSION['error_message'] = "Ocurrió un error al actualizar la misión. Por favor, intenta nuevamente.";
        }

        header("Location: mision.php?plan_id=$plan_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1. MISIÓN</title>
    <style>
        /* Estilos Generales */
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
    </style>
</head>
<body>

    <!-- Barra de progreso en pasos -->
    <div class="progress-container">
        <div class="progress-step">
            <div class="step completed">1</div>
            <div class="step-line active"></div>
            <div class="step">2</div>
            <div class="step-line"></div>
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
        <div class="content-header">1. MISIÓN</div>
        <p class="content-text">
            La <strong>MISIÓN</strong> es la razón de ser de la empresa/organización.
        </p>
        <ul>
            <li>Debe ser clara, concisa y compartida.</li>
            <li>Orientada hacia el cliente, no hacia el producto o servicio.</li>
            <li>Refleja el propósito fundamental en el mercado.</li>
        </ul>

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

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <textarea name="mision" id="mision-textarea" placeholder="En este apartado describa la Misión de su empresa." required><?php echo htmlspecialchars($mision_usuario); ?></textarea>
            <button class="save-button" type="submit">Guardar Misión</button>
        </form>

    </div>

    <!-- Botones de navegación en la parte inferior -->
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">ÍNDICE</button>
        <button class="nav-button" onclick="window.location.href='vision.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">2. VISIÓN</button>
    </div>

    <script>
        // JavaScript para actualizar la barra de progreso
        function goToStep(step) {
            const steps = document.querySelectorAll('.step');
            const stepLines = document.querySelectorAll('.step-line');

            // Limpiar las clases de pasos y líneas
            steps.forEach((s, index) => {
                if (index <= step) {
                    s.classList.add('completed');
                } else {
                    s.classList.remove('completed');
                }
            });

            // Actualizar las líneas de progreso
            stepLines.forEach((line, index) => {
                if (index < step) {
                    line.classList.add('active');
                } else {
                    line.classList.remove('active');
                }
            });

            // Cambiar de paso (simulado con un log para este ejemplo)
            console.log(`Navegando al paso ${step + 1}`);
            // Aquí podrías redirigir a la siguiente sección, por ejemplo:
            // window.location.href = `step${step + 1}.php`;
        }
    </script>

</body>
</html>
