<?php
// Incluir el archivo de conexión
require '../datos/conexion.php'; // Ajusta la ruta según sea necesario

// Verificar si hay datos POST para insertar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valores'])) {
    $valores = $_POST['valores']; // Captura los valores desde el formulario

    // Seleccionar la colección (ajusta el nombre de la colección si es necesario)
    $collection = $db->valores;

    // Verificar si el valor ya existe en la colección (para evitar duplicados)
    $existingValue = $collection->findOne(['valores' => $valores]);

    if ($existingValue) {
        echo "El valor ya existe en la base de datos.";
    } else {
        // Insertar los valores en la colección de MongoDB
        $insertResult = $collection->insertOne([
            'valores' => $valores,
            'fecha' => new MongoDB\BSON\UTCDateTime() // Añade una marca de tiempo
        ]);

        echo "Valores guardados con éxito. ID de inserción: " . $insertResult->getInsertedId();
    }
}
?>

<!-- HTML para el formulario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Valores</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Incluye tu archivo CSS aquí -->
</head>
<body>
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php'">INDICE</button>
        
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
        
        <!-- Formulario para ingresar valores -->
        <form method="POST" action="">
            <textarea name="valores" placeholder="Ingrese los valores de su empresa aquí..."></textarea>
            <button type="submit" class="save-button">Guardar Valores</button>
        </form>
    </div>

    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='vision.html'">2. VISIÓN</button>
        <button class="nav-button" onclick="window.location.href='estrategia.html'">4. ESTRATEGIA</button>
    </div>
</body>
</html>
