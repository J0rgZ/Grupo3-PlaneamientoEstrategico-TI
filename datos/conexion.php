<?php
// C:\datos\mongo_config.php

// Requiere la librería de MongoDB
require '../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

// Configuración de conexión
$uri = 'mongodb+srv://planeamiento:planeamiento@planeamientoestrategico.knzxc.mongodb.net/?retryWrites=true&w=majority&appName=PlaneamientoEstrategico';

try {
    // Crear una instancia del cliente MongoDB usando la URI proporcionada
    $client = new MongoDB\Client($uri);

    // Seleccionar la base de datos y la colección (ajusta el nombre de la base de datos y la colección según sea necesario)
    $db = $client->PlaneamientoEstrategico;
    $collection = $db->valores; // Asegúrate de usar el nombre correcto de la colección

    // Verificar si hay datos POST para insertar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valores'])) {
        $valores = $_POST['valores']; // Captura los valores desde el formulario
        
        // Insertar los valores en la colección de MongoDB
        $insertResult = $collection->insertOne([
            'valores' => $valores,
            'fecha' => new MongoDB\BSON\UTCDateTime() // Añade una marca de tiempo
        ]);
        
        echo "Valores guardados con éxito. ID de inserción: " . $insertResult->getInsertedId();
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error al conectar a MongoDB o al insertar datos: " . $e->getMessage();
}
?>
