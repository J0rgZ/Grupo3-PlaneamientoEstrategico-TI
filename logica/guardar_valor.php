<?php
require '../datos/conexion.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];
$collection = $db->diagnosticos;

// Recibir los datos enviados por Fetch API
$data = json_decode(file_get_contents('php://input'), true);
error_log("Datos recibidos: " . json_encode($data));

if (isset($data['tipo']) && isset($data['indice']) && isset($data['valor'])) {
    $tipo = $data['tipo'];
    $indice = (int)$data['indice'];
    $valor = $data['valor'];

    $user_diagnostico = $collection->findOne(['user_id' => $user_id]);

    if (!$user_diagnostico) {
        $collection->insertOne([
            'user_id' => $user_id,
            'fortalezas' => array_fill(0, 4, ''),
            'debilidades' => array_fill(0, 4, ''),
            'valores' => array_fill(0, 25, 0)
        ]);
        $user_diagnostico = $collection->findOne(['user_id' => $user_id]);
    }

    $fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
    $debilidades = (array)($user_diagnostico['debilidades'] ?? array_fill(0, 4, ''));
    $valores = (array)($user_diagnostico['valores'] ?? array_fill(0, 25, 0));

    if ($tipo === 'fortaleza') {
        $fortalezas[$indice] = (string)$valor;
    } elseif ($tipo === 'debilidad') {
        $debilidades[$indice] = (string)$valor;
    } elseif ($tipo === 'valoracion') {
        $valores[$indice] = (int)$valor;
    }

    $result = $collection->updateOne(
        ['user_id' => $user_id],
        ['$set' => [
            'fortalezas' => $fortalezas,
            'debilidades' => $debilidades,
            'valores' => $valores
        ]]
    );

    if ($result->getModifiedCount() == 0) {
        error_log("No se modificó el documento.");
    }

    $suma_total = array_sum($valores);
    $max_valor = 4 * count($valores);
    $porcentaje_mejora = (1 - ($suma_total / $max_valor)) * 100;
    $porcentaje_mejora = round($porcentaje_mejora, 2);

    echo json_encode(['success' => true, 'porcentaje_mejora' => $porcentaje_mejora]);
} else {
    echo json_encode(['error' => 'Datos no válidos']);
}
?>
