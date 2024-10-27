<?php
// Iniciar la sesión

session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirige a la página de inicio de sesión
    exit();
}
// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

// Obtener el ID del usuario logeado
$user_id = $_SESSION['user_id'];

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

// Seleccionar las colecciones necesarias
$collection = $db->valores;
$collection_mision = $db->mision;

// Obtener los valores del usuario
$valores_usuario = $collection->findOne(['user_id' => $user_id]);
$mision_usuario = $collection_mision->findOne(['user_id' => $user_id]);

// Aquí empezamos a construir el HTML
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
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 30px;
            font-size: 36px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-section {
            margin-bottom: 40px;
        }
        .form-section h2 {
            background-color: #00bcd4;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .input-section {
            border: 1px solid #00bcd4;
            padding: 20px;
            background-color: #292b2c;
            border-radius: 5px;
            color: #ffffff;
            transition: background-color 0.3s;
        }
        .input-section:hover {
            background-color: #343a40;
        }
        .input-values {
            border: 1px solid #00bcd4;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #292b2c;
            color: #ffffff;
            border-radius: 5px;
            font-style: italic;
        }
        label {
            font-weight: bold;
            color: #00bcd4;
            font-size: 18px;
        }
        .form-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .form-table td {
            padding: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #b0b0b0;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <!-- Encabezado con botones de retroceso y cierre de sesión -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Botón para regresar -->
        <a href="index.php" class="btn btn-primary">
            ← Regresar
        </a>


        <!-- Botón de Cerrar Sesión -->
        <form method="post" class="mb-0">
            <button type="submit" name="logout" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    <div class="form-section">
        <h1>Resumen Ejecutivo del Plan Estratégico</h1>

        <div class="form-section">
            <table class="form-table">
                <tr>
                    <td><label>Nombre de la empresa / proyecto:</label></td>
                    <td>
                        <div class="input-section">
                            <?php echo htmlspecialchars($valores_usuario['nombre_empresa'] ?? '[Nombre de la empresa]'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label>Fecha de elaboración:</label></td>
                    <td>
                        <div class="input-section">
                            <?php echo htmlspecialchars($valores_usuario['fecha_elaboracion'] ?? '[Fecha de elaboración]'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label>Emprendedores / Promotores:</label></td>
                    <td>
                        <div class="input-section">
                            <?php echo htmlspecialchars($valores_usuario['emprendedores'] ?? '[Emprendedores]'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="form-section">
            <h2>Misión</h2>
            <div class="input-section">
                <?php echo htmlspecialchars($mision_usuario['mision'] ?? '[Misión no ingresada]'); ?>
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
                if (is_array($valores_usuario['valores'])) {
                    foreach ($valores_usuario['valores'] as $valor) {
                        echo '<div class="input-values">' . htmlspecialchars($valor) . '</div>';
                    }
                } else {
                    echo '<div class="input-values">' . htmlspecialchars($valores_usuario['valores']) . '</div>';
                }
            } else {
                echo '<div class="input-values">[Valores no ingresados]</div>';
            }
            ?>
        </div>

        <div class="footer">
            <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
        </div>
    </div>
</div>
</body>
</html>

