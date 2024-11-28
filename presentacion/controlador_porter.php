<?php
require_once '../datos/conexion.php'; // Conexión a MongoDB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Inicializar variables
        $puntuacionTotal = 0;
        $factores = [
            'crecimiento', 'competidores', 'exceso', 'rentabilidad', 'diferenciacion',
            'barreras_salida', 'economias', 'capital', 'tecnologia', 'reglamentos',
            'numero_clientes', 'integracion', 'rentabilidad_clientes', 'coste_cambio', 'sustitutivos'
        ];

        // Recorrer los factores y sumar la puntuación seleccionada
        foreach ($factores as $factor) {
            if (isset($_POST[$factor])) {
                $puntuacionTotal += intval($_POST[$factor]); // Convertir a entero y sumar
            }
        }

        // Generar conclusiones basadas en la puntuación total
        $conclusiones = '';
        if ($puntuacionTotal <= 10) {
            $conclusiones = "La competencia en este mercado es baja. Las oportunidades para entrar al mercado son favorables.";
        } elseif ($puntuacionTotal <= 20) {
            $conclusiones = "La competencia en este mercado es moderada. Se requiere una buena estrategia para competir.";
        } else {
            $conclusiones = "La competencia en este mercado es alta. Es un mercado muy competitivo y difícil de ingresar.";
        }

        // Guardar los datos en la base de datos
        $coleccion = $db->porter_analisis; // Seleccionar colección
        $evaluacion = [
            'puntuacionTotal' => $puntuacionTotal,
            'conclusiones' => $conclusiones,
            'factores' => $_POST,
            'fecha' => new MongoDB\BSON\UTCDateTime() // Fecha de la evaluación
        ];
        $result = $coleccion->insertOne($evaluacion);

        // Responder al cliente con los resultados
        echo json_encode([
            'success' => true,
            'puntuacionTotal' => $puntuacionTotal,
            'conclusiones' => $conclusiones,
            'id' => (string)$result->getInsertedId()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Error al procesar la evaluación: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Utilice POST.'
    ]);
}
?>
