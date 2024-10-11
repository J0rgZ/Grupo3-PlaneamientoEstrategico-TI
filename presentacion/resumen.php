<?php
// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

// Seleccionar la colección de "valores" en la base de datos
$collection = $db->valores;

// Obtener los valores de la colección (asumiendo que quieres todos los valores)
$valores = $collection->find([]);

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
                    <td><div class="input-section">[Nombre de la empresa]</div></td>
                </tr>
                <tr>
                    <td><label>Fecha de elaboración:</label></td>
                    <td><div class="input-section">[Fecha de elaboración]</div></td>
                </tr>
                <tr>
                    <td><label>Emprendedores / Promotores:</label></td>
                    <td><div class="input-section">[Emprendedores]</div></td>
                </tr>
            </table>
        </div>

        <div class="form-section">
            <h2>Misión</h2>
            <div class="input-section">
                [Misión]
            </div>
        </div>

        <div class="form-section">
            <h2>Visión</h2>
            <div class="input-section">
                [Visión]
            </div>
        </div>

        <div class="form-section">
            <h2>Valores</h2>

            <?php
            // Mostrar los valores recuperados de MongoDB
            foreach ($valores as $valor) {
                echo '<div class="input-values">' . htmlspecialchars($valor['valores']) . '</div>';
            }
            ?>
            
        </div>
    </div>
</body>
</html>