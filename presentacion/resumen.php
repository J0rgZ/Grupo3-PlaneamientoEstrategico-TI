<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

// Obtener el ID del usuario logeado
$user_id = $_SESSION['user_id'];

// Obtener el plan_id desde la URL (si está disponible)
$plan_id = $_GET['plan_id'] ?? '';

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

// Inicializar las variables
$mision_usuario = '';
$vision_usuario = '';
$valores_usuario = [];

// Obtener los valores del usuario (nombre de la empresa, fecha de elaboración, etc.)
$collection = $db->valores;
$valores_usuario = $collection->findOne(['user_id' => $user_id]);

// Asegurarse de que tenemos un plan_id y luego recuperar los campos desde la base de datos
if ($plan_id) {
    try {
        $collection = $db->planes;

        // Buscar el plan en la base de datos
        $documento = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id), 'user_id' => $user_id]);

        // Si se encuentra el documento, asignar los campos
        if ($documento) {
            // Asignar la misión si existe
            if (isset($documento['mision'])) {
                $mision_usuario = $documento['mision'];
            } else {
                $mision_usuario = '[Misión no encontrada]';
            }

            // Asignar la visión si existe
            if (isset($documento['vision'])) {
                $vision_usuario = $documento['vision'];
            } else {
                $vision_usuario = '[Visión no encontrada]';
            }

            // Asignar los valores si existen
            if (isset($documento['valores'])) {
                $valores_usuario['valores'] = $documento['valores'];
            } else {
                $valores_usuario['valores'] = '[Valores no encontrados]';
            }
        } else {
            // Si no se encuentra el documento, mostrar un mensaje
            $mision_usuario = '[Misión no encontrada]';
            $vision_usuario = '[Visión no encontrada]';
            $valores_usuario['valores'] = '[Valores no encontrados]';
        }
    } catch (Exception $e) {
        error_log("Error al recuperar el plan: " . $e->getMessage());
        $mision_usuario = '[Error al obtener la misión]';
        $vision_usuario = '[Error al obtener la visión]';
        $valores_usuario['valores'] = '[Error al obtener los valores]';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Ejecutivo del Plan Estratégico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /* Contenedor de contenido */
        .valores-container {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
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

        /* Tabla y estilo de datos */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td, table th {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Estilos de campos de entrada */
        .input-section {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }

        .footer p {
            margin: 10px 0;
        }
                /* Barra de progreso en pasos */
                .progress-container {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
            margin-left: 190px;
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
    </style>
</head>
<body>

<div class="container mt-4">
        <!-- Barra de navegación de progreso -->
        <div class="progress-container">
        <div class="progress-step">
            <div class="step">1</div>
            <div class="step-line"></div>
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
            <div class="step-line active"></div>
            <div class="step completed">8</div>

        </div>
    </div>
    <div class="valores-container">
        <div class="valores-header">
            <h1>Resumen Ejecutivo del Plan Estratégico</h1>
        </div>

        <table>
            <tr>
                <td><label>Nombre de la empresa / proyecto:</label></td>
                <td><div class="input-section"><?php echo htmlspecialchars($valores_usuario['nombre_empresa'] ?? '[Nombre de la empresa]'); ?></div></td>
            </tr>
            <tr>
                <td><label>Fecha de elaboración:</label></td>
                <td><div class="input-section"><?php echo htmlspecialchars($valores_usuario['fecha_elaboracion'] ?? '[Fecha de elaboración]'); ?></div></td>
            </tr>
            <tr>
                <td><label>Emprendedores / Promotores:</label></td>
                <td><div class="input-section"><?php echo htmlspecialchars($valores_usuario['emprendedores'] ?? '[Emprendedores]'); ?></div></td>
            </tr>
        </table>

        <!-- Mostrar la Misión -->
        <div class="form-section">
            <h2>Misión</h2>
            <div class="input-section">
                <?php echo nl2br(htmlspecialchars($mision_usuario)); ?>
            </div>
        </div>

        <!-- Mostrar la Visión -->
        <div class="form-section">
            <h2>Visión</h2>
            <div class="input-section">
                <?php echo nl2br(htmlspecialchars($vision_usuario)); ?>
            </div>
        </div>

<!-- Mostrar los Valores -->
<div class="form-section">
    <h2>Valores</h2>
    <div class="input-section">
        <?php
        if (!empty($valores_usuario['valores'])) {
            if (is_array($valores_usuario['valores'])) {
                foreach ($valores_usuario['valores'] as $valor) {
                    // Mostrar cada valor individualmente
                    echo nl2br('<div class="input-values">' . htmlspecialchars($valor) . '</div>');
                }
            } else {
                // Si los valores no son un array, se muestra el valor único
                echo nl2br('<div class="input-values">' . htmlspecialchars($valores_usuario['valores']) . '</div>');
            }
        } else {
            // En caso de que no haya valores, mostrar un mensaje
            echo '<div class="input-values">[Valores no ingresados]</div>';
        }
        ?>
    </div>
</div>

        <!-- Botones de navegación -->
        <div class="navigation-buttons">
            <button onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">ÍNDICE</button>

            <button onclick="window.location.href='matriz.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">7. MATRIZ</button>

        </div>

        <div class="footer">
            <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
        </div>
    </div>
</div>

</body>
</html>
