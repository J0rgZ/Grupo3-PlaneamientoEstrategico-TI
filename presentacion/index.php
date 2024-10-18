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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .long-button {
            width: 100%;
            padding: 15px;
            background-color: #2f8fa3;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .long-button:hover {
            background-color: #276f82;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }
        h2 {
            color: #2f8fa3;
            text-align: center;
            margin-bottom: 30px;
        }
        .button-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .nav-button {
            padding: 15px;
            background-color: #2f8fa3;
            color: white;
            border: none;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .nav-button:hover {
            background-color: #276f82;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #888;
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
        
        <p>
            Esta aplicación le ayudará a reflexionar sobre la estrategia que debe llevar a cabo. Visualizará dónde quiere estar, dónde está actualmente y qué camino tendrá que trazar
            para llevarle a otro estado.
        </p>
        
        <h2>INFORMACIÓN</h2>

        <div class="button-grid">
            <button class="nav-button" onclick="window.location.href='mision.php'">1. MISIÓN</button>
            <button class="nav-button" onclick="window.location.href='vision.php'">2. VISIÓN</button>
            <button class="nav-button" onclick="window.location.href='valores.php'">3. VALORES</button>
            <button class="nav-button" onclick="window.location.href='objetivos.php'">4. OBJETIVOS</button>
            <button class="nav-button" onclick="window.location.href='analisis.php'">5. ANÁLISIS INTERNO Y EXTERNO</button>
            <button class="nav-button" onclick="window.location.href='cadena.php'">6. CADENA DE VALOR</button>
            <button class="nav-button" onclick="window.location.href='matriz.php'">7. MATRIZ PARTICIPACIÓN</button>
            <button class="nav-button" onclick="window.location.href='fuerzas.php'">8. LAS 5 FUERZAS DE PORTER</button>
            <button class="nav-button" onclick="window.location.href='pest.php'">9. PEST</button>
            <button class="nav-button" onclick="window.location.href='estrategia.php'">10. IDENTIFICACIÓN ESTRATEGIA</button>
            <button class="nav-button" onclick="window.location.href='came.php'">11. MATRIZ CAME</button>
        </div>

        <button class="long-button" style="margin-top: 30px;" onclick="window.location.href='resumen.php'">RESUMEN DEL PLAN EJECUTIVO</button>
    </div>

    <footer>
        <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
