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

$plan_id = $_GET['plan_id'] ?? '';
$matriz_came = [];

// Recuperar datos de la matriz CAME desde MongoDB
try {
    $collection = $db->planes;
    $documento = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id), 'user_id' => $_SESSION['user_id']]);
    if ($documento && isset($documento['matriz_came'])) {
        $matriz_came = $documento['matriz_came'];
    }
} catch (Exception $e) {
    error_log("Error al recuperar matriz CAME: " . $e->getMessage());
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matriz_came = $_POST['matriz_came'] ?? [];

    try {
        $result = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
            ['$set' => [
                'matriz_came' => $matriz_came,
                'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
            ]]
        );

        $_SESSION['success_message'] = "Matriz CAME actualizada exitosamente.";
    } catch (Exception $e) {
        error_log("Error al actualizar matriz CAME: " . $e->getMessage());
        $_SESSION['error_message'] = "Ocurrió un error al actualizar la matriz CAME.";
    }

    header("Location: matrizcame.php?plan_id=$plan_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>11. Matriz CAME</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal */
        .content-container {
            width: 90%;
            max-width: 1000px;
            background: white;
            border-radius: 8px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Header */
        .header {
            text-align: center;
            padding: 10px;
            background-color: #0099cc;
            color: white;
            border-radius: 8px;
            font-size: 1.5em;
        }

        /* Barra de progreso en pasos */
        .progress-container {
            display: flex;
            justify-content: center;
            align-items: center; /* Asegura que los elementos estén alineados verticalmente */
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
        }

        .progress-step {
            display: flex;
            align-items: center;
            justify-content: center; /* Alinea horizontalmente los elementos */
            gap: 10px; /* Espaciado uniforme entre los steps */
            width: 100%; /* Asegura que ocupe todo el contenedor */
        }

        .step {
            width: 30px;
            height: 30px;
            background-color: #d1d1d1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center; /* Centra el texto dentro del círculo */
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


        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f0f0f0;
        }

        textarea {
            width: 100%;
            height: 120px;
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9em;
            resize: none;
            background-color: #f9f9f9;
        }

        textarea::placeholder {
            color: #aaa;
            font-style: italic;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #0099cc;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #007ba7;
        }

        .success-message, .error-message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
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
            <div class="step completed">4</div>
            <div class="step-line active"></div>
            <div class="step completed">5</div>
            <div class="step-line active"></div>
            <div class="step completed">6</div>
            <div class="step-line active"></div>
            <div class="step completed">7</div>
            <div class="step-line active"></div>
            <div class="step completed">8</div>
            <div class="step-line active"></div>
            <div class="step completed">9</div>
            <div class="step-line active"></div>
            <div class="step completed">10</div>
            <div class="step-line active"></div>
            <div class="step completed">11</div>
            
        </div>
    </div>

    <div class="content-container">
        <div class="header">11. Matriz CAME</div>

        <p>
            Reflexione y anote las acciones para corregir las debilidades, afrontar las amenazas,
            mantener las fortalezas y explotar las oportunidades de su plan estratégico.
        </p>

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

        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Corregir Debilidades</td>
                        <td>
                            <textarea name="matriz_came[debilidades]" placeholder="Escriba las acciones para corregir debilidades aquí..."><?php echo htmlspecialchars($matriz_came['debilidades'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Afrontar Amenazas</td>
                        <td>
                            <textarea name="matriz_came[amenazas]" placeholder="Escriba las acciones para afrontar amenazas aquí..."><?php echo htmlspecialchars($matriz_came['amenazas'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Mantener Fortalezas</td>
                        <td>
                            <textarea name="matriz_came[fortalezas]" placeholder="Escriba las acciones para mantener fortalezas aquí..."><?php echo htmlspecialchars($matriz_came['fortalezas'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Explotar Oportunidades</td>
                        <td>
                            <textarea name="matriz_came[oportunidades]" placeholder="Escriba las acciones para explotar oportunidades aquí..."><?php echo htmlspecialchars($matriz_came['oportunidades'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="button-container">
                <button type="submit">Guardar</button>
                <button type="button" class="nav-button" onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
                    ÍNDICE
                </button>
            </div>
        </form>
    </div>
</body>
</html>
