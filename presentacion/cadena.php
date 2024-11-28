<?php
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirige a la página de inicio de sesión
    exit();
}
// Verifica si el usuario está logeado (por ejemplo, a través de una sesión)
if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}
$plan_id = $_GET['plan_id'] ?? '';

$user_id = $_SESSION['user_id'];
$collection = $db->diagnosticos;

// Obtener los datos guardados del usuario
$user_diagnostico = $collection->findOne(['user_id' => $user_id]);

// Valores de valoraciones, fortalezas y debilidades
$valores = $user_diagnostico['valores'] ?? array_fill(0, 25, 0); // 25 preguntas
$fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
$debilidades = (array)($user_diagnostico['debilidades'] ?? array_fill(0, 4, ''));
$valores = (array)$user_diagnostico['valores'] ?? array_fill(0, 25, 0);


// Cálculo inicial del porcentaje de mejora
$suma_total = array_sum($valores);
$max_valor = 100; // El máximo es 100 si todos los valores fueran 4
$porcentaje_mejora = 1 - ($suma_total / $max_valor);
$porcentaje_mejora = round($porcentaje_mejora * 100, 2); // Convertir a porcentaje y redondear
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico de la Cadena de Valor Interna</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
    <style>
        /* Estilos Generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin-top: 30px;
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .btn-primary, .btn-danger {
            font-size: 1em;
            padding: 8px 20px;
            border-radius: 5px;
        }
        .progress-container {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
            margin-left: 10px;
        }

        .progress-step {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .step {
            width: 30px;
            height: 30px;
            background-color: #d1d1d1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .step.completed {
            background-color: #0099cc;
        }

        .step-line {
            flex: 1;
            height: 4px;
            background-color: #d1d1d1;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .step-line.active {
            background-color: #0099cc;
        }
        .table {
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table td select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .styled-table {
            width: 100%;
            margin-top: 20px;
        }

        .styled-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .styled-table .label {
            font-weight: bold;
        }

        .styled-table .input-field {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .potencial-container {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            font-size: 1.2em;
            text-align: center;
        }

        .potencial-container .porcentaje {
            font-weight: bold;
            color: #0099cc;
        }

        .section-title {
            font-size: 1.6em;
            margin-top: 30px;
            font-weight: bold;
            color: #333;
        }

        .form-section {
            margin-bottom: 40px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }

        .footer p {
            margin: 10px 0;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
        }

        button {
            background-color: #0099cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
        }
        .content-header {
            background-color: #0099cc;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.6em;
            margin-bottom: 20px;
            margin-left: 250px;
        }
        .content-text {
            font-size: 1.1em;
            color: #333;
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: center;
            .progress-step {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


    </style>
   
    <script>
        // Función para guardar automáticamente los valores y recalcular el porcentaje
        function guardarValor(tipo, indice, valor) {
            // Enviar los datos al servidor usando Fetch API
            fetch('../logica/guardar_valor.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tipo: tipo,
                    indice: indice,
                    valor: valor
                })
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                if (data.success) {
                    // Actualizar el porcentaje de mejora en la página
                    document.getElementById('potencial').innerText = 'Potencial de Mejora de la Cadena de Valor Interna: ' + data.porcentaje_mejora + '%';
                } else {
                    console.error('Error al guardar los datos:', data.error);
                }
            })
            .catch(error => {
                console.error('Error al guardar automáticamente:', error);
            });
        }


        // Asignar el evento de cambio para recalcular el porcentaje al cambiar la valoración
        function calcularPorcentajeInicial() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const indice = this.name.replace('valor_', '');
                    guardarValor('valoracion', indice, this.value);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', calcularPorcentajeInicial);
    </script>
</head>

<body>
    <div class="progress-container">
        <div class="progress-step">
            <div class="step">1</div>
            <div class="step-line"></div>
            <div class="step">2</div>
            <div class="step-line"></div>
            <div class="step">3</div>
            <div class="step-line"></div>
            <div class="step">4</div>
            <div class="step-line"></div>
            <div class="step">5</div>
            <div class="step-line active"></div>
            <div class="step completed">6</div>
            <div class="step-line"></div>
            <div class="step">7</div>
            <div class="step-line"></div>
            <div class="step">8</div>

        </div>
    </div>
    <div class="container mt-4">
        <!-- Encabezado con botones de retroceso y cierre de sesión -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Botón para regresar -->

            <div class="content-header">Diagnóstico de la Cadena de Valor Interna</div>


        </div>

        <form id="diagnosticoForm">
            <div class="form-section">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Diagnóstico</th>
                            <th>Valoración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $preguntas = [
                            "La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.",
                            "La empresa amplía los medios productivos tecnológicamente más avanzados de su sector.",
                            "La empresa dispone de un sistema de información y control de gestión eficiente y eficaz.",
                            "Los medios técnicos y tecnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.",
                            "La empresa es un referente en su sector en I+D+i.",
                            "La excelencia de los procedimientos de la empresa (en ISO, etc.) son una principal fuente de ventaja competitiva.",
                            "La empresa dispone de página web, y esta se emplea no solo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.",
                            "Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.",
                            "La empresa es referente en su sector en la optimización, en términos de coste, de su cadena de producción, siendo esta una de sus principales ventajas competitivas.",
                            "La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.",
                            "Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.",
                            "Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.",
                            "La empresa dispone y ejecuta un sistemático plan de marketing y ventas.",
                            "La empresa tiene optimizada su gestión financiera.",
                            "La empresa busca continuamente mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo.",
                            "La empresa es referente en su sector en el lanzamiento de innovadores productos y servicios de éxito demostrado en el mercado.",
                            "Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.",
                            "Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.",
                            "La empresa siempre trabaja conforme a una estrategia y objetivos claros.",
                            "La gestión del circulante está optimizada.",
                            "Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.",
                            "Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.",
                            "La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día.",
                            "Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.",
                            "El servicio al cliente que prestamos es una de nuestras principales ventajas competitivas respecto a nuestros competidores."
                        ];


                        $opciones = [
                            "0 = En total desacuerdo",
                            "1 = No está de acuerdo",
                            "2 = Está de acuerdo",
                            "3 = Bastante de acuerdo",
                            "4 = En total acuerdo"
                        ];

                        foreach ($preguntas as $i => $pregunta) {
                            echo "<tr><td>" . ($i + 1) . "</td>";
                            echo "<td>" . htmlspecialchars($pregunta) . "</td><td>";

                            $valorSeleccionado = $valores[$i] ?? -1;
                            echo "<select name='valor_$i' onchange='guardarValor(\"valoracion\", $i, this.value)' class='form-select'>";

                            echo "<option value='' disabled selected>Seleccione una opción</option>"; // Opción por defecto
                            foreach ($opciones as $j => $opcion) {
                                $selected = ($valorSeleccionado == $j) ? 'selected' : '';
                                echo "<option value='$j' $selected>$opcion</option>";
                            }

                            echo "</select></td></tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Mostrar el porcentaje de mejora calculado -->
        <div id="potencial" class="potencial-container">
            Potencial de Mejora de la Cadena de Valor Interna: <span class="porcentaje"><?php echo $porcentaje_mejora; ?>%</span>
        </div>

        <!-- Fortalezas -->
        <h2 class="section-title">Fortalezas</h2>
        <table class="styled-table">
            <?php for ($i = 0; $i < 2; $i++): ?>
                <tr>
                    <td class="label">F<?php echo $i + 1; ?>:</td>
                    <td>
                        <input type="text" name="fortaleza_<?php echo $i; ?>" 
                               value="<?php echo htmlspecialchars($fortalezas[$i]); ?>" 
                               oninput="guardarValor('fortaleza', <?php echo $i; ?>, this.value)" 
                               class="input-field">
                    </td>
                </tr>
            <?php endfor; ?>
        </table>

        <!-- Debilidades -->
        <h2 class="section-title">Debilidades</h2>
        <table class="styled-table">
            <?php for ($i = 0; $i < 2; $i++): ?>
                <tr>
                    <td class="label">D<?php echo $i + 1; ?>:</td>
                    <td>
                        <input type="text" name="debilidad_<?php echo $i; ?>" 
                               value="<?php echo htmlspecialchars($debilidades[$i]); ?>" 
                               oninput="guardarValor('debilidad', <?php echo $i; ?>, this.value)" 
                               class="input-field">
                    </td>
                </tr>
            <?php endfor; ?>
        </table>

    </div>
        <!-- Botones de navegación --><br>
        <div class="navigation-buttons">
            <button onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">ÍNDICE</button>
            <button onclick="window.location.href='analisis.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">5. ANALISIS</button>
            <button onclick="window.location.href='matriz.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">7. MATRIZ</button>
        </div>
    <div class="footer">
        <p>&copy; 2024 Cadena de Valor Interna. Todos los derechos reservados.</p>
    </div>

</body>

</html>
