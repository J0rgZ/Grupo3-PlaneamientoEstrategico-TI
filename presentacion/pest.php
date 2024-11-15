<?php
session_start();
require '../datos/conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

$user_id = $_SESSION['user_id'];
$plan_id = $_GET['plan_id'] ?? null; // Obtener el plan_id de la URL

if (!$plan_id) {
    die("No se especificó un plan.");
}

// Inicializar la colección
$collection = $db->planes;

// Función para obtener el plan actual
function obtenerPlan($collection, $plan_id) {
    return $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id)]);
}

// Función para inicializar la estructura PEST si no existe
function inicializarPEST($collection, $plan_id) {
    $pest_structure = [
        'pest_analysis' => [
            'politicos' => '',
            'economicos' => '',
            'sociales' => '',
            'tecnologicos' => '',
            'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
        ],
        'pest_autodiagnostico' => array_fill(0, 12, 0), // 12 preguntas inicializadas en 0
        'pest_oportunidades' => [
            'O3' => '',
            'O4' => ''
        ],
        'pest_amenazas' => [
            'A3' => '',
            'A4' => ''
        ]
    ];

    $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
        ['$setOnInsert' => $pest_structure],
        ['upsert' => true]
    );
}

// Inicializar PEST si es necesario
inicializarPEST($collection, $plan_id);

// Manejar el envío del formulario de análisis PEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pest_analysis'])) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
            ['$set' => [
                'pest_analysis' => [
                    'politicos' => $_POST['politicos'] ?? '',
                    'economicos' => $_POST['economicos'] ?? '',
                    'sociales' => $_POST['sociales'] ?? '',
                    'tecnologicos' => $_POST['tecnologicos'] ?? '',
                    'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
                ]
            ]]
        );
        
        header("Location: pest.php?plan_id=" . $plan_id);
        exit();
    }

    // Manejar el autodiagnóstico
    if (isset($_POST['autodiagnostico'])) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
            ['$set' => [
                'pest_autodiagnostico' => array_values($_POST['autodiagnostico']),
                'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
            ]]
        );
        
        header("Location: pest.php?plan_id=" . $plan_id);
        exit();
    }

    // Manejar oportunidades y amenazas
    if (isset($_POST['oportunidades']) || isset($_POST['amenazas'])) {
        $update = [];
        
        if (isset($_POST['oportunidades'])) {
            $update['pest_oportunidades'] = [
                'O3' => $_POST['oportunidades']['O3'] ?? '',
                'O4' => $_POST['oportunidades']['O4'] ?? ''
            ];
        }
        
        if (isset($_POST['amenazas'])) {
            $update['pest_amenazas'] = [
                'A3' => $_POST['amenazas']['A3'] ?? '',
                'A4' => $_POST['amenazas']['A4'] ?? ''
            ];
        }
        
        $update['fecha_modificacion'] = new MongoDB\BSON\UTCDateTime();
        
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
            ['$set' => $update]
        );
        
        header("Location: pest.php?plan_id=" . $plan_id);
        exit();
    }
}

// Obtener datos actuales para mostrar en el formulario
$plan = obtenerPlan($collection, $plan_id);

// Función para calcular promedios del autodiagnóstico por categoría
function calcularPromediosPEST($autodiagnostico) {
    // Validar si el autodiagnóstico es un BSONArray y convertirlo a un array nativo
    if ($autodiagnostico instanceof MongoDB\Model\BSONArray) {
        $autodiagnostico_array = (array) $autodiagnostico->getArrayCopy();
    } else if (is_array($autodiagnostico)) {
        $autodiagnostico_array = $autodiagnostico;
    } else {
        $autodiagnostico_array = [];
    }

    // Definir categorías
    $categorias = [
        'Social' => array_slice($autodiagnostico_array, 0, 3),
        'Económico' => array_slice($autodiagnostico_array, 3, 3),
        'Político' => array_slice($autodiagnostico_array, 6, 3),
        'Tecnológico' => array_slice($autodiagnostico_array, 9, 3)
    ];

    // Calcular promedios
    $promedios = [];
    foreach ($categorias as $categoria => $valores) {
        if (count($valores) > 0) {
            $promedios[$categoria] = array_sum($valores) / count($valores);
        } else {
            $promedios[$categoria] = 0;
        }
    }

    return $promedios;
}

// Calcular promedios para el gráfico si hay datos
$promedios_pest = [];
if (isset($plan['pest_autodiagnostico'])) {
    $promedios_pest = calcularPromediosPEST($plan['pest_autodiagnostico']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis Externo PEST y Autodiagnóstico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            background-color: #fff;
            margin: 20px auto;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #007ba7;
            text-align: center;
            margin-bottom: 20px;
        }

        p.description {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 25px;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007ba7;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        textarea {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            resize: vertical;
            min-height: 80px;
        }

        .nav-button {
            background-color: #007ba7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 10px;
        }

        .nav-button:hover {
            background-color: #005f85;
        }

        .form-section {
            margin-bottom: 40px;
        }

        input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sección 1: Análisis PEST -->
        <section class="form-section">
            <h1>Análisis Externo: PEST</h1>
            <p class="description">
                <strong>PEST</strong> es un acrónimo que representa el macroentorno de la empresa. Este análisis considera:
            </p>
            <ul>
                <li><strong>Políticos:</strong> Factores como legislación tributaria, tratados comerciales, normas medioambientales, etc.</li>
                <li><strong>Económicos:</strong> Tasas de interés, niveles de deuda, índices de ahorro, etc.</li>
                <li><strong>Sociales:</strong> Cambios en estilos de vida, demografía, ingresos, factores religiosos y éticos, etc.</li>
                <li><strong>Tecnológicos:</strong> Automatización, tasas de obsolescencia, impacto de TI, etc.</li>
            </ul>

            <p class="description">
                El siguiente gráfico refleja la valoración obtenida en cada una de las variables del diagnóstico macroentorno:
            </p>

            <div style="text-align: center;">
                <img src="ruta-a-tu-grafico-generado.png" alt="Gráfico PEST" style="max-width: 100%; height: auto;">
            </div>

            <form action="pest.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" method="POST">
                <input type="hidden" name="pest_analysis" value="1">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <table>
                    <tr>
                        <th>Factor</th>
                        <th>Descripción</th>
                    </tr>
                    <tr>
                        <td>Políticos</td>
                        <td>
                            <textarea name="politicos" placeholder="Describe los factores políticos aquí..."><?php echo htmlspecialchars($plan['pest_analysis']['politicos'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Económicos</td>
                        <td>
                            <textarea name="economicos" placeholder="Describe los factores económicos aquí..."><?php echo htmlspecialchars($plan['pest_analysis']['economicos'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Sociales</td>
                        <td>
                            <textarea name="sociales" placeholder="Describe los factores sociales aquí..."><?php echo htmlspecialchars($plan['pest_analysis']['sociales'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Tecnológicos</td>
                        <td>
                            <textarea name="tecnologicos" placeholder="Describe los factores tecnológicos aquí..."><?php echo htmlspecialchars($plan['pest_analysis']['tecnologicos'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                </table>

                <div class="buttons-container">
                    <button type="submit" class="nav-button">Guardar Análisis PEST</button>
                </div>
                <section class="form-section">
                    <h1>Resultados del Análisis PEST</h1>
                    <?php if (isset($resultados)): ?>
                        <div class="success-message">
                            <p><strong>Resultados calculados:</strong></p>
                            <ul>
                                <li><strong>Políticos:</strong> <?php echo number_format($resultados['politicos'], 2); ?></li>
                                <li><strong>Económicos:</strong> <?php echo number_format($resultados['economicos'], 2); ?></li>
                                <li><strong>Sociales:</strong> <?php echo number_format($resultados['sociales'], 2); ?></li>
                                <li><strong>Tecnológicos:</strong> <?php echo number_format($resultados['tecnologicos'], 2); ?></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p>No hay resultados aún. Complete el formulario y guarde el análisis para ver los cálculos.</p>
                    <?php endif; ?>
                </section>
            </form>
        </section>

        <!-- Sección 2: Autodiagnóstico -->
        <section class="form-section">
            <h1>Autodiagnóstico Entorno Global P.E.S.T.</h1>
            <form action="pest.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" method="POST">
                <input type="hidden" name="autodiagnostico" value="1">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <table>
                    <thead>
                        <tr>
                            <th>Autodiagnóstico</th>
                            <th colspan="5" class="rating-label">Valoración</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>En total desacuerdo (0)</th>
                            <th>No está de acuerdo (1)</th>
                            <th>Está de acuerdo (2)</th>
                            <th>Está bastante de acuerdo (3)</th>
                            <th>En total acuerdo (4)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $preguntas = [
                            "1. Los cambios en la composición ética de los consumidores de nuestra región.",
                            "2. El envejecimiento de la población tiene un importante impacto en la demanda.",
                            "3. Los nuevos estilos de vida y tendencias originan cambios en la oferta del sector.",
                            "4. El envejecimiento de la población tiene un importante impacto en el consumo de nuestros productos.",
                            "5. El nivel de riqueza de la población impacta significativamente en la demanda de productos/servicios.",
                            "6. Los tipos de interés y la inflación afectan la economía del sector.",
                            "7. La legislación fiscal afecta muy considerablemente a la economía del sector donde operamos.",
                            "8. La presión regulatoria de las Administraciones Públicas puede afectar las estrategias empresariales.",
                            "9. Las normas medioambientales restringen las operaciones de la industria.",
                            "10. La normativa autonómica tiene un impacto considerable en el sector.",
                            "11. La política de apoyo a las empresas impacta en el desarrollo financiero del sector.",
                            "12. La globalización permite a nuestra industria explorar nuevas oportunidades."
                        ];

                        foreach ($preguntas as $index => $pregunta) {
                            echo "<tr>
                                <td>$pregunta</td>";
                            for ($i = 0; $i <= 4; $i++) {
                                $checked = isset($plan['pest_autodiagnostico'][$index]) && $plan['pest_autodiagnostico'][$index] == $i ? 'checked' : '';
                                echo "<td style='text-align: center;'>
                                        <input type='radio' name='autodiagnostico[$index]' value='$i' required $checked>
                                      </td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="buttons-container">
                    <button type="submit" class="nav-button">Guardar Autodiagnóstico</button>
                </div>
            </form>
        </section>

        <!-- Sección 3: Oportunidades y Amenazas -->
        <section class="form-section">
            <h1>Oportunidades y Amenazas</h1>
            <p class="description">
                A partir de la conclusión obtenida en el diagnóstico en cada uno de los factores, determine las oportunidades y amenazas más relevantes que desee que se reflejen en el análisis FODA de su Plan Estratégico.
            </p>

            <form action="pest.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <table>
                    <tr>
                        <th colspan="2" style="background-color: #ffdab9;">OPORTUNIDADES</th>
                    </tr>
                    <tr>
                        <td>O3:</td>
                        <td>
                            <input type="text" name="oportunidades[O3]" value="<?php echo htmlspecialchars($plan['pest_oportunidades']['O3'] ?? ''); ?>" placeholder="Escriba aquí...">
                        </td>
                    </tr>
                    <tr>
                        <td>O4:</td>
                        <td>
                            <input type="text" name="oportunidades[O4]" value="<?php echo htmlspecialchars($plan['pest_oportunidades']['O4'] ?? ''); ?>" placeholder="Escriba aquí...">
                        </td>
                    </tr>
                </table>

                <table style="margin-top: 20px;">
                    <tr>
                        <th colspan="2" style="background-color: #add8e6;">AMENAZAS</th>
                    </tr>
                    <tr>
                        <td>A3:</td>
                        <td>
                            <input type="text" name="amenazas[A3]" value="<?php echo htmlspecialchars($plan['pest_amenazas']['A3'] ?? ''); ?>" placeholder="Escriba aquí...">
                        </td>
                    </tr>
                    <tr>
                        <td>A4:</td>
                        <td>
                            <input type="text" name="amenazas[A4]" value="<?php echo htmlspecialchars($plan['pest_amenazas']['A4'] ?? ''); ?>" placeholder="Escriba aquí...">
                        </td>
                    </tr>
                </table>

                <div class="buttons-container">
                    <button type="button" class="nav-button" onclick="window.location.href='porter.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>';">
                        8. ANÁLISIS PORTER
                    </button>
                    <button type="submit" class="nav-button">
                        10. IDENTIFICACIÓN DE ESTRATEGIAS
                    </button>
                </div>
            </form>
        </section>
    </div>
</body>
</html>