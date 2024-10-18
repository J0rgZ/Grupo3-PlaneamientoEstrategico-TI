<?php
session_start();

// Verificar si el usuario está logueado y si el token CSRF es válido
if (!isset($_SESSION['user_id']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: ../login.php");
    exit();
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mision = trim($_POST['mision']);

    if (!empty($mision)) {
        try {
            $collection = $db->mision;

            // Actualizar o insertar la misión del usuario
            $result = $collection->updateOne(
                ['user_id' => $_SESSION['user_id']],
                ['$set' => ['mision' => $mision]],
                ['upsert' => true]
            );

            // Mensaje de éxito
            $_SESSION['success_message'] = "La misión ha sido guardada con éxito.";
        } catch (Exception $e) {
            // Manejar errores de conexión o consulta
            error_log("Error al guardar la misión: " . $e->getMessage());
            $_SESSION['error_message'] = "Ocurrió un error al guardar tu misión. Por favor, intenta nuevamente.";
        }
    } else {
        $_SESSION['error_message'] = "La misión no puede estar vacía.";
    }

    header("Location: ../paginas/mision.php");
    exit();
}
?>
