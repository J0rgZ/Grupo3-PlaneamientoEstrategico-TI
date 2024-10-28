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
    $collection = $db->planes; // Cambiar a la colección 'planes'

    // Buscar el documento del plan donde 'user_id' y 'plan_id' coincidan
    $documento = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id), 'user_id' => $_SESSION['user_id']]);

    if ($documento && isset($documento['vision'])) {
        // Asignar la visión existente a la variable
        $vision_usuario = $documento['vision'];
    }
} catch (Exception $e) {
    error_log("Error al recuperar visión: " . $e->getMessage());
    $_SESSION['error_message'] = "Error: " . $e->getMessage(); // Muestra el mensaje de error
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_vision = $_POST['vision'] ?? '';
    $plan_id = $_GET['plan_id'] ?? ''; // Obtener el plan_id del formulario

    if ($nueva_vision) {
        try {
            // Actualizar la visión en el plan
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

        // Redirigir a la misma página para evitar reenvío del formulario
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
    <title>2. VISIÓN</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9f1f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .mision-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            border-radius: 12px;
        }

        .mision-header {
            background-color: #007acc;
            color: white;
            padding: 15px;
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 12px 12px 0 0;
        }

        .mision-text {
            font-size: 1.2em;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 15px;
            font-size: 1.5em;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            resize: none;
            transition: border-color 0.3s;
        }

        textarea:focus {
            border-color: #007acc;
            outline: none;
        }

        .save-button {
            background-color: #007acc;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
            margin-top: 10px;
        }

        .save-button:hover {
            background-color: #005f99;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .success-message, .error-message {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }

        .examples {
            margin-top: 20px;
        }

        .diagram {
            margin-top: 30px;
            text-align: center;
        }

        .diagram-circle {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 50%;
            background-color: #007acc;
            color: white;
            margin: 10px;
        }

        .diagram-arrow {
            display: inline-block;
            margin: 10px;
            font-weight: bold;
        }

        .diagram-questions {
            margin-top: 20px;
            font-size: 1em;
        }

        .diagram-questions span {
            display: block;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="mision-container">
        <header>
            <a href="index.php" class="indice">ÍNDICE</a>
        </header>
        
        <main>
            <h1 class="mision-header">2. VISIÓN</h1>
            
            <p class="mision-text">
                La <strong>VISIÓN</strong> de una empresa define lo que la empresa/organización quiere lograr en el futuro. Es lo que la organización aspira llegar a ser en torno a 2-3 años.
            </p>
            
            <ul>
                <li>Debe ser retadora, positiva, compartida y coherente con la misión.</li>
                <li>Marca el fin último que la estrategia debe seguir.</li>
                <li>Proyecta la imagen de destino que se pretende alcanzar.</li>
            </ul>
            
            <p class="mision-text">
                La visión debe ser conocida y compartida por todos los miembros de la empresa y también por aquellos que se relacionan con ella.
            </p>
            
            <div class="examples">
                <h2>EJEMPLOS</h2>
                <p><strong>Empresa de servicios</strong><br>
                Ser el grupo empresarial de referencia en nuestras áreas de actividad.</p>
                
                <p><strong>Empresa productora de café</strong><br>
                Queremos ser en el mundo el punto de referencia de la cultura y de la excelencia del café. Una empresa innovadora que propone los mejores productos y lugares de consumo y que, gracias a ello, crece y se convierte en líder de la alta gama.</p>
                
                <p><strong>Agencia de certificación</strong><br>
                Ser líderes en nuestro sector y un actor principal en todos los segmentos de mercado en los que estamos presentes, en los mercados clave.</p>
            </div>
            
            <div class="vision-input">
                <form method="POST" action="vision.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>">
                    <textarea name="vision" rows="4" required placeholder="Describe la Visión de tu empresa."><?php echo htmlspecialchars($vision_usuario); ?></textarea>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" class="save-button">Guardar Visión</button>
                </form>
            </div>

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
            
            <div class="diagram">
                <p><strong>Relación entre Misión y Visión</strong></p>
                <div class="diagram-circle">Misión</div>
                <div class="diagram-arrow">Procesos cotidianos</div>
                <div class="diagram-circle">Visión</div>
                <div class="diagram-questions">
                    <span>¿Cuál es la situación actual?</span>
                    <span>¿Qué camino a seguir?</span>
                    <span>¿Cuál es la situación futura?</span>
                </div>
            </div>
        </main>
    </div>
</body>
</html>


