<?php
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$collection = $db->diagnosticos;

// Recibir los datos enviados por Fetch API
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['tipo']) && isset($data['indice']) && isset($data['valor'])) {
    $tipo = $data['tipo'];  // Puede ser 'fortaleza', 'debilidad', o 'valoracion'
    $indice = (int)$data['indice']; // Índice de la fortaleza, debilidad o valoración
    $valor = $data['valor']; // Dejar el valor como cadena de texto

    // Obtener el diagnóstico del usuario
    $user_diagnostico = $collection->findOne(['user_id' => $user_id]);

    // Convertir BSON a arrays nativos de PHP
    $fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
    $debilidades = (array)($user_diagnostico['debilidades'] ?? array_fill(0, 4, ''));
    $valores = (array)($user_diagnostico['valores'] ?? array_fill(0, 25, 0));

    // Actualizar según el tipo
    if ($tipo === 'fortaleza') {
        $fortalezas[$indice] = (string)$valor; // Asegurarse de que sea una cadena de texto
    } elseif ($tipo === 'debilidad') {
        $debilidades[$indice] = (string)$valor; // Asegurarse de que sea una cadena de texto
    } elseif ($tipo === 'valoracion') {
        $valores[$indice] = (int)$valor; // Las valoraciones deben ser enteros
    }

    // Guardar todo junto en MongoDB
    $collection->updateOne(
        ['user_id' => $user_id],
        ['$set' => [
            'fortalezas' => $fortalezas,
            'debilidades' => $debilidades,
            'valores' => $valores
        ]]
    );

    // Calcular el porcentaje de mejora en base a las valoraciones
    $suma_total = array_sum($valores);
    $max_valor = 100; // 100 puntos es el máximo si todas las valoraciones fueran 4
    $porcentaje_mejora = 1 - ($suma_total / $max_valor);
    $porcentaje_mejora = round($porcentaje_mejora * 100, 2); // Convertir a porcentaje y redondear

    // Enviar el porcentaje de mejora como respuesta JSON
    echo json_encode(['success' => true, 'porcentaje_mejora' => $porcentaje_mejora]);
} else {
    echo json_encode(['error' => 'Datos no válidos']);
}
?>
