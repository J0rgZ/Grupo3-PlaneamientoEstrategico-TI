<?php
// C:\datos\mongo_config.php

// Requiere la librería de MongoDB
require '../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

// Configuración de conexión
$uri = 'mongodb+srv://planeamiento:planeamiento@planeamientoestrategico.knzxc.mongodb.net/?retryWrites=true&w=majority&appName=PlaneamientoEstrategico';

try {
    // Crear una instancia del cliente MongoDB usando la URI proporcionada
    $client = new MongoDB\Client($uri);

    // Seleccionar la base de datos (ajusta el nombre de la base de datos si es necesario)
    $db = $client->PlaneamientoEstrategico;

    // Probar la conexión (opcional)
    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $db->command($command);
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error al conectar a MongoDB: " . $e->getMessage());
}
?>
