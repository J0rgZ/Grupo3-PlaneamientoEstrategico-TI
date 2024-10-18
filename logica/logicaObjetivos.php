<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Verificar el token CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF inválido.";
    header("Location: ../presentacion/objetivos.php");
    exit();
}

// Capturar y limpiar los datos del formulario
$mision = trim($_POST['mision']);
$objetivos_generales = [];
$objetivos_especificos = [];

// Capturar los objetivos generales y específicos
for ($i = 1; $i <= 3; $i++) {
    $objetivos_generales[] = trim($_POST['objetivo_general_' . $i]);
    $objetivos_especificos[$i + 1] = [
        trim($_POST['objetivo_especifico_' . $i . '_1']),
        trim($_POST['objetivo_especifico_' . $i . '_2']),
    ];
}

// Validar que se haya ingresado al menos un objetivo
if (!empty($mision) && !empty(array_filter($objetivos_generales))) {
    try {
        // Seleccionar la colección de MongoDB
        $collection = $db->objetivos;

        // Recuperar el ID del usuario desde la sesión
        $user_id = $_SESSION['user_id'];

        // Definir los datos a guardar
        $datos_a_guardar = [
            'user_id' => $user_id,
            'mision' => $mision,
            'objetivos_generales' => $objetivos_generales,
            'objetivos_especificos' => $objetivos_especificos,
            'fecha' => new MongoDB\BSON\UTCDateTime()
        ];

        // Definir opciones, como upsert (insertar si no existe)
        $opciones = ['upsert' => true];

        // Guardar o actualizar los objetivos
        $resultado = $collection->updateOne(['user_id' => $user_id], ['$set' => $datos_a_guardar], $opciones);

        // Verificar si la operación fue exitosa
        if ($resultado->getModifiedCount() > 0 || $resultado->getUpsertedCount() > 0) {
            $_SESSION['success_message'] = "Objetivos guardados exitosamente.";
        } else {
            $_SESSION['error_message'] = "No se realizaron cambios en los objetivos.";
        }

    } catch (Exception $e) {
        // Manejar errores de conexión o actualización
        error_log("Error al guardar/actualizar objetivos: " . $e->getMessage());
        $_SESSION['error_message'] = "Ocurrió un error al guardar los objetivos. Por favor, intenta nuevamente.";
    }
} else {
    // Manejar el caso donde no se ingresaron datos válidos
    $_SESSION['error_message'] = "Por favor, completa todos los campos antes de continuar.";
}

// Redirigir al formulario
header("Location: ../presentacion/objetivos.php");
exit();
