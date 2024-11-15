<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Manejar el envío del formulario de análisis PEST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (isset($_POST['politicos'])) {
        $analisis_pest = [
            'politicos' => $_POST['politicos'] ?? '',
            'economicos' => $_POST['economicos'] ?? '',
            'sociales' => $_POST['sociales'] ?? '',
            'tecnologicos' => $_POST['tecnologicos'] ?? '',
        ];

        // Lógica para guardar el análisis PEST (pendiente)
        $_SESSION['success_message'] = "Análisis PEST guardado correctamente.";
        header("Location: pest.php");
        exit();
    }

    if (isset($_POST['autodiagnostico'])) {
        $autodiagnostico_pest = $_POST['autodiagnostico'] ?? [];

        // Lógica para guardar el autodiagnóstico PEST (pendiente)
        $_SESSION['success_message'] = "Autodiagnóstico PEST guardado correctamente.";
        header("Location: pest.php");
        exit();
    }
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
        /* Estilos generales */
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
            resize: none;
        }

        .nav-button {
            background-color: #007ba7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .nav-button:hover {
            background-color: #005f85;
        }
    </style>
</head>
<body>
    <div class="container">
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

        <!-- Gráfico -->
        <div style="text-align: center;">
            <img src="ruta-a-tu-grafico-generado.png" alt="Gráfico PEST" style="max-width: 100%; height: auto;">
        </div>

        <!-- Formulario para Análisis PEST -->
        <form action="pest.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <table>
                <tr>
                    <th>Factor</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>Políticos</td>
                    <td>
                        <textarea name="politicos" placeholder="Describe los factores políticos aquí..."></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Económicos</td>
                    <td>
                        <textarea name="economicos" placeholder="Describe los factores económicos aquí..."></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Sociales</td>
                    <td>
                        <textarea name="sociales" placeholder="Describe los factores sociales aquí..."></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Tecnológicos</td>
                    <td>
                        <textarea name="tecnologicos" placeholder="Describe los factores tecnológicos aquí..."></textarea>
                    </td>
                </tr>
            </table>

            <button type="submit" class="nav-button">Guardar Análisis</button>
        </form>

        <h1>Autodiagnóstico Entorno Global P.E.S.T.</h1>

        <!-- Formulario para Autodiagnóstico -->
        <form action="pest.php" method="POST">
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
                    // Preguntas del autodiagnóstico
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
                        "12. La globalización permite a nuestra industria explorar nuevas oportunidades.",
                    ];

                    foreach ($preguntas as $index => $pregunta) {
                        echo "<tr>
                            <td>$pregunta</td>";
                        for ($i = 0; $i <= 4; $i++) {
                            echo "<td style='text-align: center;'>
                                    <input type='radio' name='autodiagnostico[$index]' value='$i' required>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="container">
                <h1>Oportunidades y Amenazas</h1>
                <p class="description">
                    A partir de la conclusión obtenida en el diagnóstico en cada uno de los factores, determine las oportunidades y amenazas más relevantes que desee que se reflejen en el análisis FODA de su Plan Estratégico.
                </p>

                <form action="pest.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <table>
                        <tr>
                            <th colspan="2" style="background-color: #ffdab9; text-align: center;">OPORTUNIDADES</th>
                        </tr>
                        <tr>
                            <td>O3:</td>
                            <td><input type="text" name="oportunidades[O3]" placeholder="Escriba aquí..." style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>O4:</td>
                            <td><input type="text" name="oportunidades[O4]" placeholder="Escriba aquí..." style="width: 100%;"></td>
                        </tr>
                    </table>

                    <table style="margin-top: 20px;">
                        <tr>
                            <th colspan="2" style="background-color: #add8e6; text-align: center;">AMENAZAS</th>
                        </tr>
                        <tr>
                            <td>A3:</td>
                            <td><input type="text" name="amenazas[A3]" placeholder="Escriba aquí..." style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>A4:</td>
                            <td><input type="text" name="amenazas[A4]" placeholder="Escriba aquí..." style="width: 100%;"></td>
                        </tr>
                    </table>

                    <div style="margin-top: 20px; text-align: center;">
                        <button type="button" class="nav-button" onclick="window.location.href='porter.php';">8. ANÁLISIS PORTER</button>
                        <button type="submit" class="nav-button">10. IDENTIFICACIÓN DE ESTRATEGIAS</button>
                    </div>
                </form>
            </div>


            <button type="submit" class="nav-button">Guardar Evaluación</button>
        </form>
    </div>
</body>
</html>
