<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

// Obtener el ID del usuario logeado
$user_id = $_SESSION['user_id'];

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

// Seleccionar la colección de "valores" en la base de datos, filtrando por el user_id
$collection = $db->valores;

// Obtener los valores del usuario que está autenticado
$valores_usuario = $collection->findOne(['user_id' => $user_id]);

// Aquí empezamos a construir el HTML
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Ejecutivo del Plan Estratégico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 70%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #0058a3;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h2 {
            background-color: #0058a3;
            color: #fff;
            padding: 10px;
            margin: 0;
            text-transform: uppercase;
        }
        .input-section {
            border: 2px solid #0058a3;
            padding: 15px;
            margin-top: 10px;
            background-color: #e9e9e9;
            min-height: 20px;
        }
        .form-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .form-table td {
            padding: 10px 0;
        }
        .input-values {
            border: 2px solid #0058a3;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #e9e9e9;
            height: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resumen Ejecutivo del Plan Estratégico</h1>

        <div class="form-section">
            <table class="form-table">
                <tr>
                    <td><label>Nombre de la empresa / proyecto:</label></td>
                    <td><div class="input-section">
                        <?php echo htmlspecialchars($valores_usuario['nombre_empresa'] ?? '[Nombre de la empresa]'); ?>
                    </div></td>
                </tr>
                <tr>
                    <td><label>Fecha de elaboración:</label></td>
                    <td><div class="input-section">
                        <?php echo htmlspecialchars($valores_usuario['fecha_elaboracion'] ?? '[Fecha de elaboración]'); ?>
                    </div></td>
                </tr>
                <tr>
                    <td><label>Emprendedores / Promotores:</label></td>
                    <td><div class="input-section">
                        <?php echo htmlspecialchars($valores_usuario['emprendedores'] ?? '[Emprendedores]'); ?>
                    </div></td>
                </tr>
            </table>
        </div>

        <div class="form-section">
            <h2>Misión</h2>
            <div class="input-section">
                <?php echo htmlspecialchars($valores_usuario['mision'] ?? '[Misión]'); ?>
            </div>
        </div>

        <div class="form-section">
            <h2>Visión</h2>
            <div class="input-section">
                <?php echo htmlspecialchars($valores_usuario['vision'] ?? '[Visión]'); ?>
            </div>
        </div>

        <div class="form-section">
            <h2>Valores</h2>
            <?php
            // Mostrar los valores recuperados de MongoDB, si existen
            if (!empty($valores_usuario['valores'])) {
                // Verificar si es un arreglo antes de usar foreach
                if (is_array($valores_usuario['valores'])) {
                    foreach ($valores_usuario['valores'] as $valor) {
                        echo '<div class="input-values">' . htmlspecialchars($valor) . '</div>';
                    }
                } else {
                    // Si no es un arreglo, mostrarlo directamente como texto
                    echo '<div class="input-values">' . htmlspecialchars($valores_usuario['valores']) . '</div>';
                }
            } else {
                echo '<div class="input-values">[Valores no ingresados]</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
