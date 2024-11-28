<?php
// Asegurarse de que el usuario esté autenticado
session_start();
require '../datos/conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

// Obtener el plan_id de la solicitud
$plan_id = $_POST['plan_id'] ?? null;
$fortalezas = $_POST['fortalezas'] ?? [];
$debilidades = $_POST['debilidades'] ?? [];

if (!$plan_id) {
    die("No se especificó un plan.");
}

// Asegúrate de que las fortalezas y debilidades tengan la longitud correcta
$fortalezas = array_pad($fortalezas, 2, ''); // Asegurarse de que haya 4 fortalezas
$debilidades = array_pad($debilidades, 2, ''); // Asegurarse de que haya 4 debilidades

// Inicializar la colección
$collection = $db->diagnosticos;

// Actualizar los valores de fortalezas y debilidades en la base de datos
$updateResult = $collection->updateOne(
    ['plan_id' => $plan_id], // Condición para encontrar el diagnóstico por plan_id
    ['$set' => [
        'fortalezas' => $fortalezas,
        'debilidades' => $debilidades
    ]]
);

// Verificar si la actualización fue exitosa
if ($updateResult->getModifiedCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo actualizar']);
}
