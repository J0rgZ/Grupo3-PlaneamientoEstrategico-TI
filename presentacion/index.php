<?php

session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    require '../datos/conexion.php';
    exit();
}

// Obtener el id_plan si está presente en la URL
$id_plan = htmlspecialchars($_GET['plan_id'] ?? '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Estratégico</title>
    <style>
    /* General Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
    }

    body {
        background-color: #f4f4f9;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        width: 90%;
        max-width: 600px;
        padding: 30px;
        background-color: #ffffff;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    /* Header Button */
    .long-button {
        display: inline-block;
        width: 100%;
        padding: 15px;
        font-size: 1.2em;
        font-weight: bold;
        color: #ffffff;
        background-color: #0073e6;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 20px;
        transition: background-color 0.3s;
    }

    .long-button:hover {
        background-color: #005bb5;
    }

    /* Paragraph Style */
    p {
        font-size: 1em;
        line-height: 1.6;
        color: #555;
        margin-bottom: 20px;
    }

    /* Section Title */
    h2 {
        font-size: 1.4em;
        color: #333;
        margin-bottom: 20px;
        font-weight: bold;
    }

    /* Grid of Buttons */
    .button-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .nav-button {
        padding: 12px;
        font-size: 0.9em;
        color: #333;
        background-color: #e0e0e0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s, background-color 0.3s;
    }

    .nav-button:hover {
        background-color: #d3d3d3;
        transform: translateY(-2px);
    }

    /* Footer */
    footer {
        margin-top: 30px;
        font-size: 0.9em;
        color: #555;
    }

    footer p {
        color: #888;
        font-size: 0.8em;
    }
</style>
</head>
<body>

    <div class="container">
        <button class="long-button">CÓMO ELABORAR UN PLAN ESTRATÉGICO</button>
        
        <p>
            El éxito de las organizaciones reside en gran parte en la capacidad que tienen sus directivos de ejecutar una estrategia más que en la calidad de la estrategia en sí.
            La planificación y asignación de recursos son fundamentales para el logro de la misma. En este sentido, un Plan Estratégico puede entenderse como el conjunto de acciones
            que han de llevarse a cabo para alinear los recursos y potencialidades con el fin de conseguir el estado deseado, es decir, la adaptación y adquisición de competitividad empresarial.
        </p>
        
        <h2>INFORMACIÓN</h2>

        <div class="button-grid">
            <button class="nav-button" onclick="window.location.href='mision.php?plan_id=<?php echo $id_plan; ?>'">1. MISIÓN</button>
            <button class="nav-button" onclick="window.location.href='vision.php?plan_id=<?php echo $id_plan; ?>'">2. VISIÓN</button>
            <button class="nav-button" onclick="window.location.href='valores.php?plan_id=<?php echo $id_plan; ?>'">3. VALORES</button>
            <button class="nav-button" onclick="window.location.href='objetivos.php?plan_id=<?php echo $id_plan; ?>'">4. OBJETIVOS</button>
            <button class="nav-button" onclick="window.location.href='analisis.php?plan_id=<?php echo $id_plan; ?>'">5. ANÁLISIS INTERNO Y EXTERNO</button>
            <button class="nav-button" onclick="window.location.href='cadena.php?plan_id=<?php echo $id_plan; ?>'">6. CADENA DE VALOR</button>
            <button class="nav-button" onclick="window.location.href='matriz.php?plan_id=<?php echo $id_plan; ?>'">7. MATRIZ PARTICIPACIÓN</button>
            <button class="nav-button" onclick="window.location.href='fuerzas.php?plan_id=<?php echo $id_plan; ?>'">8. LAS 5 FUERZAS DE PORTER</button>
            <button class="nav-button" onclick="window.location.href='pest.php?plan_id=<?php echo $id_plan; ?>'">9. PEST</button>
            <button class="nav-button" onclick="window.location.href='estrategia.php?plan_id=<?php echo $id_plan; ?>'">10. IDENTIFICACIÓN ESTRATEGIA</button>
            <button class="nav-button" onclick="window.location.href='came.php?plan_id=<?php echo $id_plan; ?>'">11. MATRIZ CAME</button>
        </div>

        <button class="long-button" style="margin-top: 30px;" onclick="window.location.href='resumen.php?plan_id=<?php echo $id_plan; ?>'">RESUMEN DEL PLAN EJECUTIVO</button>
    </div>

    <footer>
        <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
