<?php
session_start();

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar si se ha recibido una solicitud POST con los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vision'])) {

    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Token CSRF inválido.";
        header("Location: ../presentacion/vision.php");
        exit();
    }

    // Capturar y limpiar los datos del formulario
    $vision = trim($_POST['vision']);

    // Validar que el campo 'vision' no esté vacío
    if ($vision !== "") {
        try {
            // Seleccionar la colección de MongoDB
            $collection = $db->vision;

            // Recuperar el ID del usuario desde la sesión
            $user_id = $_SESSION['user_id'];

            // Definir el filtro para buscar el documento del usuario
            $filtro = ['user_id' => $user_id];

            // Definir los datos a actualizar
            $datos_actualizados = [
                '$set' => [
                    'vision' => $vision,
                    'fecha' => new MongoDB\BSON\UTCDateTime()
                ]
            ];

            // Definir opciones, como upsert (insertar si no existe)
            $opciones = ['upsert' => true];

            // Realizar la actualización (o inserción si no existe)
            $resultado = $collection->updateOne($filtro, $datos_actualizados, $opciones);

            // Verificar si la operación fue exitosa
            if ($resultado->getModifiedCount() > 0 || $resultado->getUpsertedCount() > 0) {
                $_SESSION['success_message'] = "Visión guardada exitosamente.";
            } else {
                $_SESSION['error_message'] = "No se realizaron cambios en la visión.";
            }

        } catch (Exception $e) {
            error_log("Error al guardar/actualizar visión: " . $e->getMessage());
            $_SESSION['error_message'] = "Ocurrió un error al guardar la visión. Por favor, intenta nuevamente.";
        }
    } else {
        $_SESSION['error_message'] = "Por favor, ingresa la visión antes de continuar.";
    }

    // Redirigir de vuelta a la página de visión
    header("Location: ../presentacion/vision.php");
    exit();

} else {
    // Si no se recibe una solicitud POST válida, redirigir al formulario
    header("Location: ../presentacion/vision.php");
    exit();
}
?>
