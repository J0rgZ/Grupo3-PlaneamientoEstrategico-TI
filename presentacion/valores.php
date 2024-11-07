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

// Inicializar la variable de valores
$valores_usuario = "";

// Verificar si se ha recibido 'plan_id' desde la solicitud
$plan_id = $_GET['plan_id'] ?? '';

// Recuperar los valores existentes del usuario y plan desde MongoDB
try {
    $collection = $db->planes;

    $filtro = [
        'user_id' => $_SESSION['user_id'],
        '_id' => new MongoDB\BSON\ObjectId($plan_id)
    ];
    
    $documento = $collection->findOne($filtro);

    if ($documento && isset($documento['valores'])) {
        $valores_usuario = $documento['valores'];
    } else {
        $_SESSION['error_message'] = "No se encontraron valores para el plan especificado.";
    }
} catch (Exception $e) {
    error_log("Error al recuperar valores: " . $e->getMessage());
    $_SESSION['error_message'] = "Ocurrió un error al recuperar tus valores. Por favor, intenta nuevamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3. VALORES</title>
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
        .valores-container {
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

        .valores-header {
            background-color: #0099cc;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 20px;
        }

        .valores-text {
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

        .success-message {
            color: #4caf50;
            margin-bottom: 10px;
        }

        .error-message {
            color: #f44336;
            margin-bottom: 10px;
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
            <div class="step completed">3</div>
            <div class="step-line active"></div>
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

    <div class="valores-container">
        <h2 class="valores-header">3. VALORES</h2>
        <p class="valores-text">
            Los <strong>VALORES</strong> de una empresa son el conjunto de principios, reglas y aspectos culturales con los que se rige la organización.
        </p>
        <ul>
            <li>Integridad</li>
            <li>Compromiso con el desarrollo humano</li>
            <li>Ética profesional</li>
            <li>Responsabilidad social</li>
            <li>Innovación</li>
        </ul>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para los valores -->
        <form method="POST" action="../logica/logicaValores.php" id="valoresForm">
            <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" id="planId" name="plan_id" value="<?php echo htmlspecialchars($plan_id); ?>">
            <textarea id="valoresInput" name="valores" placeholder="Ingrese los valores de su empresa aquí..." required><?php echo htmlspecialchars($valores_usuario); ?></textarea>
            
            <div class="navigation-buttons">
                <button type="submit" name="action" value="index">ÍNDICE</button>
                <button type="submit" name="action" value="vision">2. VISIÓN</button>
                <button type="submit" name="action" value="resumen">4. RESUMEN</button>
            </div>
        </form>

    </div>

    <script>
        // JavaScript para actualizar la barra de progreso
        function goToStep(step) {
            const steps = document.querySelectorAll('.step');
            const lines = document.querySelectorAll('.step-line');

            for (let i = 0; i < steps.length; i++) {
                if (i < step) {
                    steps[i].classList.add('completed');
                    if (i > 0) lines[i - 1].classList.add('active');
                } else {
                    steps[i].classList.remove('completed');
                }
            }
        }

        // Ejemplo: Llamar a la función para mostrar el progreso actual
        goToStep(3); // Cambia este número para reflejar el progreso actual
    </script>
</body>
</html>

