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
$amenazas = $user_diagnostico['amenazas'] ?? array_fill(0, 25, 0); // 25 preguntas
$fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
$debilidades = (array)($user_diagnostico['debilidad'] ?? array_fill(0, 4, ''));
$oportunidades = (array)$user_diagnostico['oportunidades'] ?? array_fill(0, 25, 0);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación de Estrategias</title>
    <link rel="stylesheet" href="estilos.css">
   
</head>
<body>
    <div class="valores-container">
        <div class="valores-header">
            <h1>10. Identificación de Estrategias</h1>
        </div>
        <div class="valores-text">
            <p>Tras el análisis realizado habiéndose identificado las oportunidades, amenazas, fortalezas y debilidades, es momento de identificar la estrategia que debe seguir su empresa para el logro de sus objetivos empresariales.</p>
            <p>Se trata de realizar una Matriz Cruzada tal y como se refleja en el siguiente diagrama para identificar la estrategia más conveniente a llevar a cabo.</p>
        </div>
        <img src="../img/matrizdafo.png" alt="matriz_dafo" width="1000" height="auto">
        <div class="valores-examples">
            <div class="matriz-general">
            <p>Según ha ido cumplimentando en las fases anteriores, los factores internos y externos  de su empresa son los siguientes: </p>
            <table>
    <thead>
        <tr>
            <th>Debilidades</th>
            <th>Amenazas</th>
            <th>Fortalezas</th>
            <th>Oportunidades</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < 4; $i++): ?>
            <tr>
                <td>
                    <input type="text" name="debilidad_<?php echo $i; ?>" 
                           value="<?php echo htmlspecialchars($debilidades[$i]); ?>" 
                           oninput="guardarValor('debilidad', <?php echo $i; ?>, this.value)" 
                           class="input-field">
                </td>
                <td>
                    <input type="text" name="amenaza_<?php echo $i; ?>" 
                           value="<?php echo htmlspecialchars($amenazas[$i]); ?>" 
                           oninput="guardarValor('amenaza', <?php echo $i; ?>, this.value)" 
                           class="input-field">
                </td>
                <td>
                    <input type="text" name="fortaleza_<?php echo $i; ?>" 
                           value="<?php echo htmlspecialchars($fortalezas[$i]); ?>" 
                           oninput="guardarValor('fortaleza', <?php echo $i; ?>, this.value)" 
                           class="input-field">
                </td>
                <td>
                    <input type="text" name="oportunidad_<?php echo $i; ?>" 
                           value="<?php echo htmlspecialchars($oportunidades[$i]); ?>" 
                           oninput="guardarValor('oportunidad', <?php echo $i; ?>, this.value)" 
                           class="input-field">
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>

    <form method="POST" action="">
            <?php
            // Definir matrices
            $matrices = [
                "fortalezas_oportunidades" => [
                    "title" => "Matriz Fortalezas vs Oportunidades",
                    "description" => "Las fortalezas se usan para tomar ventaja en cada una las oportunidades.",
                    "rows" => ["F1", "F2", "F3", "F4"],
                    "cols" => ["O1", "O2", "O3", "O4"]
                ],
                "fortalezas_amenazas" => [
                    "title" => "Matriz Fortalezas vs Amenazas",
                    "description" => "Las fortalezas evaden el efecto negativo de las amenazas.",
                    "rows" => ["F1", "F2", "F3", "F4"],
                    "cols" => ["A1", "A2", "A3", "A4"]
                ],
                "debilidades_oportunidades" => [
                    "title" => "Matriz Debilidades vs Oportunidades",
                    "description" => "Superamos las debilidades tomando ventaja de las oportunidades.",
                    "rows" => ["D1", "D2", "D3", "D4"],
                    "cols" => ["O1", "O2", "O3", "O4"]
                ],
                "debilidades_amenazas" => [
                    "title" => "Matriz Debilidades vs Amenazas",
                    "description" => "Las debilidades intensifican notablemente el efecto negativo de las amenazas.",
                    "rows" => ["D1", "D2", "D3", "D4"],
                    "cols" => ["A1", "A2", "A3", "A4"]
                ]
            ];

            // Procesar totales para todas las tablas si se envió el formulario
            $totals = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                foreach ($matrices as $matrixKey => $matrix) {
                    $totals[$matrixKey] = array_fill(0, count($matrix['cols']), 0);
                    if (isset($_POST[$matrixKey])) {
                        foreach ($_POST[$matrixKey] as $row) {
                            foreach ($row as $colIndex => $value) {
                                $colNum = (int)filter_var($colIndex, FILTER_SANITIZE_NUMBER_INT) - 1;
                                $totals[$matrixKey][$colNum] += (int)$value;
                            }
                        }
                    }
                }
            }

            // Renderizar tablas
            foreach ($matrices as $matrixKey => $matrix) {
                echo "<div class='matriz'>";
                echo "<h2>{$matrix['title']}</h2>";
                echo "<p>{$matrix['description']}<br>0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</p>";
                echo "<table border='0'>";
                echo "<thead><tr><th>{$matrix['rows'][0]}</th>";
                foreach ($matrix['cols'] as $col) {
                    echo "<th>{$col}</th>";
                }
                echo "</tr></thead>";
                echo "<tbody>";

                foreach ($matrix['rows'] as $rowIndex => $row) {
                    echo "<tr>";
                    echo "<td>{$row}</td>";
                    foreach ($matrix['cols'] as $colIndex => $col) {
                        $value = $_POST[$matrixKey][$rowIndex][$col] ?? 0;
                        echo "<td><input type='number' name='{$matrixKey}[{$rowIndex}][{$col}]' min='0' max='4' value='{$value}'></td>";
                    }
                    echo "</tr>";
                }

                echo "<tr><td>Total</td>";
                foreach ($matrix['cols'] as $colIndex => $col) {
                    $total = $totals[$matrixKey][$colIndex] ?? 0;
                    echo "<td>{$total}</td>";
                }
                echo "</tr>";

                echo "</tbody></table>";
                echo "</div>";
            }
            ?>
            <button type="submit">Calcular Totales</button>
        </form>

            <div class="sintesis-resultados">
            <h2>Síntesis de Resultados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Relaciones</th>
                        <th>Tipología de Estrategia</th>
                        <th>Puntuación</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FO</td>
                        <td>Estrategia Ofensiva</td>
                        <td><input type="number" min="0" max="4"></td>
                        <td>Deberá adoptar estrategias de crecimiento</td>
                    </tr>
                    <tr>
                        <td>AF</td>
                        <td>Estrategia Defensiva</td>
                        <td><input type="number" min="0" max="4"></td>
                        <td>La empresa está preparada para enfrentarse a las amenazas</td>
                    </tr>
                    <tr>
                        <td>AD</td>
                        <td>Estrategia de Supervivencia</td>
                        <td><input type="number" min="0" max="4"></td>
                        <td>Se enfrenta a amenazas externas sin las fortalezas necesarias para luchar con la competencia</td>
                    </tr>
                    <tr>
                        <td>OD</td>
                        <td>Estrategia de Reorientación</td>
                        <td><input type="number" min="0" max="4"></td>
                        <td>La empresa no puede aprovechar las oportunidades porque carece de preparación adecuada</td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>

        

        <div class="navigation-buttons">
            <button class="nav-button" onclick="window.location.href='pest.php'">9. PEST</button>
            <button class="nav-button" onclick="window.location.href='matriz_came.php'">11. Matriz CAME</button>
        </div>
    </div>
</body>
</html>
