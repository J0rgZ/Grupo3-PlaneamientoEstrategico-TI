<?php

session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
    <link rel="stylesheet" href="estilos.css"> <!-- Incluye tu archivo CSS aquí -->
    <style>
        /* ... estilos ... */
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
