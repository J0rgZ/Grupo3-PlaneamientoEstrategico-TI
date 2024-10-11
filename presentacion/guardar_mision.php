<?php
// Incluimos el archivo de conexión
require '../datos/conexion.php';

// Verificamos que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos la misión ingresada por el usuario
    $mision = $_POST['mision'];

    // Insertamos la misión en la base de datos
    try {
        $coleccion = $db->misiones;  // Asume que tienes una colección llamada 'misiones'
        $resultado = $coleccion->insertOne([
            'mision' => $mision,
            'fecha' => new MongoDB\BSON\UTCDateTime()
        ]);

        echo "Misión guardada correctamente.";
    } catch (Exception $e) {
        echo "Error al guardar la misión: " . $e->getMessage();
    }
}
?>
