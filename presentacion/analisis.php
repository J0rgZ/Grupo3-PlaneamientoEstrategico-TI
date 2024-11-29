<?php
// Simulación de datos obtenidos dinámicamente
$analisisExterno = [
    "Amenazas" => "Competencia fuerte, regulaciones",
    "Oportunidades" => "Nuevas tecnologías, expansión de mercado"
];
$analisisInterno = [
    "Debilidades" => "Bajos recursos financieros",
    "Fortalezas" => "Reputación de marca, calidad del producto"
];
$plan_id = $_POST['plan_id'];
$plan_id_encoded = urlencode($plan_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis Interno y Externo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="valores-container">
        <h2 class="valores-header">5. ANÁLISIS INTERNO Y EXTERNO</h2>
        <!-- Reemplazo del texto descriptivo aquí -->
        <p class="valores-text">Fijados los objetivos estratégicos se debe analizar las distintas estrategias para lograrlos. De esta forma, las estrategias son los caminos, vías, o enfoques para alcanzar los objetivos. Responden a la pregunta ¿cómo?.</p>
        <p class="valores-text">Para determinar la estrategia, podríamos basarnos en el conjunto de estrategias genéricas y específicas que diferentes profesionales proponen al respecto. Esta guía, lejos de rozar la teoría, propone llevar a cabo un análisis interno y externo de su empresa para obtener una matriz cruzada e identificar la estrategia más conveniente a llevar a cabo.</p>
        <p class="valores-text">Este análisis le permitirá detectar por un lado los factores de éxito (fortalezas y oportunidades), y por otro lado, las debilidades y amenazas que una empresa debe gestionar.</p>
        <img src="../img/analisis1.png" alt="analisis1" width="1000" height="auto">

        <div class="analysis-box">
            <div class="box">
                <h3>Análisis Externo</h3>
                <?php foreach($analisisExterno as $clave => $valor): ?>
                    <p><strong><?php echo $clave; ?>:</strong> <?php echo $valor; ?></p>
                <?php endforeach; ?>
            </div>
            <div class="box">
                <h3>Análisis Interno</h3>
                <?php foreach($analisisInterno as $clave => $valor): ?>
                    <p><strong><?php echo $clave; ?>:</strong> <?php echo $valor; ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="footer-box">Análisis de Recursos y Capacidades de la Empresa</div>

        <div class="foda">
            <h3>FODA</h3>
            <div class="foda-section">
                <h4>Análisis Externo</h4>
                <p>Microentorno (A.E. Sectorial): Las 5 Fuerzas de Porter</p>
                <p>Macroentorno (A.E. Global): PEST</p>
            </div>
            <div class="foda-section">
                <h4>Análisis Interno</h4>
                <p>Cadena de Valor</p>
                <p>Matriz de Participación - Crecimiento de BCG</p>
            </div>
        </div>
        <img src="../img/analisis2.png" alt="analisis1" width="1000" height="auto">
    </div>

    <div class="navigation-buttons">
        <form action="index.php?plan_id=<?php echo $plan_id_encoded; ?>" method="get" style="flex: 1; margin: 0 5px;">
            <button type="submit" class="nav-button">ÍNDICE</button>
        </form>
        <form action="objetivos.php?plan_id=<?php echo $plan_id_encoded; ?>" method="get" style="flex: 1; margin: 0 5px;">
            <button type="submit" class="nav-button">4. OBJETIVOS</button>
        </form>
        <form action="cadena.php?plan_id=<?php echo $plan_id_encoded; ?>" method="get" style="flex: 1; margin: 0 5px;">
            <button type="submit" class="nav-button">6. CADENA DE VALOR</button>
        </form>
    </div>
</body>
</html>
