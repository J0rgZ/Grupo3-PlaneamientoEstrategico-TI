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
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
        }

        table td {
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .form-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        #resultado {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        #potencial {
            font-weight: bold;
            color: #007BFF;
            text-align: center;
            margin-top: 20px;
        }

        #error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 0.8em;
            }
        }
        input[type="text"] {
            border: none;
            width: 100%;
            box-sizing: border-box; /* Esto asegura que los márgenes y padding no rompan el diseño */
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
    <h1>Diagnóstico de la Cadena de Valor Interna</h1>
    <form method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
    <p>
        A continuación marque con una X para valorar su empresa en función de cada una de las afirmaciones, de tal forma que:
        <br>0 = En total en desacuerdo,
        <br>1 = No está de acuerdo,
        <br>2 = Está de acuerdo,
        <br>3 = Está bastante de acuerdo,
        <br>4 = En total acuerdo.
    </p>

    <!-- Formulario para las valoraciones -->
    <form id="diagnosticoForm" method="post">
        <table>
            <tr>
                <th>#</th>
                <th>Diagnóstico</th>
                <th>Valoración</th>
            </tr>
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

            echo "Preguntas count: " . count($preguntas) . "<br>";
            echo "Valores count: " . count($valores) . "<br>";


            for ($i = 0; $i < count($preguntas); $i++) {
                echo "<tr>";
                echo "<td>" . ($i + 1) . "</td>";
                echo "<td>" . htmlspecialchars($preguntas[$i]) . "</td>";
                echo "<td>";
            
                $valorSeleccionado = isset($valores[$i]) ? $valores[$i] : -1;
            
                for ($j = 0; $j < 5; $j++) {
                    $checked = ($valorSeleccionado == $j) ? 'checked' : '';
                    // Asociamos bien el name e identificador
                    echo "<input type='radio' id='valor_{$i}_{$j}' name='valor_$i' value='$j' $checked 
                          onchange=\"guardarValor('valoracion', $i, this.value)\"> $j ";
                }
            
                echo "</td>";
                echo "</tr>";
            }
            
            ?>
        </table>
    </form>

    <!-- Mostrar el porcentaje de mejora calculado -->
    <div id="potencial">
        Potencial de Mejora de la Cadena de Valor Interna: <?php echo $porcentaje_mejora; ?>%
    </div>

    <p>
    <br>Reflexione sobre el resultado obtenido. Anote aquellas observaciones que puedan ser de su interés. Identifique sus fortalezas y debilidades respecto a su cadena de valor 
    </p>
    <!-- Sección de Fortalezas -->
    <h2>Fortalezas</h2>
    <table>
        <tr>
            <td>F1:</td>
            <td><input type="text" name="fortaleza_0" value="<?php echo htmlspecialchars($fortalezas[0]); ?>" oninput="guardarValor('fortaleza', 0, this.value)"></td>
            </tr>
        <tr>
            <td>F2:</td>
            <td><input type="text" name="fortaleza_1" value="<?php echo htmlspecialchars($fortalezas[1]); ?>" oninput="guardarValor('fortaleza', 1, this.value)"></td>
        </tr>
        <tr>
            <td>F3:</td>
            <td><input type="text" name="fortaleza_2" value="<?php echo htmlspecialchars($fortalezas[2]); ?>" oninput="guardarValor('fortaleza', 2, this.value)"></td>
        </tr>
        <tr>
            <td>F4:</td>
            <td><input type="text" name="fortaleza_3" value="<?php echo htmlspecialchars($fortalezas[3]); ?>" oninput="guardarValor('fortaleza', 3, this.value)"></td>
        </tr>
    </table>

    <!-- Sección de Debilidades -->
    <h2>Debilidades</h2>
    <table>
        <tr>
            <td>D1:</td>
            <td><input type="text" name="debilidad_0" value="<?php echo htmlspecialchars($debilidades[0]); ?>" oninput="guardarValor('debilidad', 0, this.value)"></td>
        </tr>
        <tr>
            <td>D2:</td>
            <td><input type="text" name="debilidad_1" value="<?php echo htmlspecialchars($debilidades[1]); ?>" oninput="guardarValor('debilidad', 1, this.value)"></td>
        </tr>
        <tr>
            <td>D3:</td>
            <td><input type="text" name="debilidad_2" value="<?php echo htmlspecialchars($debilidades[2]); ?>" oninput="guardarValor('debilidad', 2, this.value)"></td>
        </tr>
        <tr>
            <td>D4:</td>
            <td><input type="text" name="debilidad_3" value="<?php echo htmlspecialchars($debilidades[3]); ?>" oninput="guardarValor('debilidad', 3, this.value)"></td>
        </tr>
    </table>
</body>
</html>
