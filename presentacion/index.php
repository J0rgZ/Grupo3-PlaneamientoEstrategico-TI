<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Estratégico</title>
    <!-- Enlaza el archivo CSS externo -->
    <link rel="stylesheet" href="estilos.css"> <!-- Incluye tu archivo CSS aquí -->
</head>
<body>

    <button class="long-button">CÓMO ELABORAR UN PLAN ESTRATÉGICO</button>
    
    <p>
        <?php 
        echo "El éxito de las organizaciones reside en gran parte en la capacidad que tienen sus directivos en ejecutar una estrategia más que en la calidad de la estrategia en sí. 
        Su planificación y asignación de recursos son fundamentales para el logro de la misma. En este sentido, un Plan Estratégico puede entenderse como el conjunto de acciones 
        que han de llevarse a cabo para alinear los recursos y potencialidades al objeto de conseguir el estado deseado, es decir, adaptación y adquisición de competitividad empresarial."; 
        ?>
    </p>
    
    <p>
        <?php 
        echo "Esta aplicación le ayudará a reflexionar sobre la estrategia que debe llevar a cabo. Visualizará dónde quiere estar, dónde está actualmente y, qué camino tendrá que trazar 
        para llevarle a otro estado."; 
        ?>
    </p>
    
    <p><strong>Para visualizar dónde quiere estar su empresa tendrá que definir:</strong></p>
    <p>1- Misión <br> 2- Visión <br> 3- Valores</p>

    <p><strong>Para entender dónde está tendrá que llevar a cabo un doble análisis:</strong></p>
    <p>1- Análisis interno <br> 2- Análisis externo</p>

    <p><strong>Para trazar el camino para ir de un punto a otro tendrá que:</strong></p>
    <p>1- Identificar la estrategia más conveniente <br> 2- Determinar acciones para facilitar el logro de la estrategia</p>

    <p>
        <em><?php echo "Gracias al Plan Empresarial determinará la forma de lograr una ventaja competitiva para su proyecto de inversión."; ?></em>
    </p>
    
    <button class="long-button">INFORMACIÓN</button>

    <div class="button-grid">
    <button class="nav-button" onclick="window.location.href='mision.php'">1. MISIÓN</button>
        <button class="nav-button" onclick="window.location.href='vision.php'">2. VISIÓN</button>
        <button class="nav-button" onclick="window.location.href='valores.php'">3. VALORES</button>
        <button class="nav-button" onclick="window.location.href='objetivos.php'">4. OBJETIVOS</button>
    </div>
    
    <button class="nav-button" onclick="window.location.href='resumen.php'">RESUMEN DEL PLAN EJECUTIVO</button>

</body>
</html>
