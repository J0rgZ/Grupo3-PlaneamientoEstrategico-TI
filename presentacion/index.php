<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener el id_plan si está presente en la URL
$id_plan = $_GET['plan_id'] ?? '';

// Si el ID no es válido, guardar cambios y redirigir
if (!preg_match('/^[0-9a-f]{24}$/', $id_plan)) {
    $_SESSION['mensaje'] = "ID de plan no válido. Redirigiendo a inicio.";
    header('Location: inicio.php');
    exit();
}

// Requiere la conexión a MongoDB
require '../datos/conexion.php'; // Ruta de tu archivo de conexión

// Función para obtener el nombre del plan desde la base de datos
function getPlanName($id_plan, $db) {
    $collection = $db->planes; // Ajusta según el nombre de tu colección
    $plan = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id_plan)]);
    return $plan ? $plan['nombre'] : 'Plan Desconocido'; // Ajusta según tu estructura
}

// Obtener el nombre del plan
$plan_name = getPlanName($id_plan, $db);
$user_name = $_SESSION['username'] ?? 'Usuario'; // Nombre del usuario

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Estratégico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Fuente moderna -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        header {
            background-color: #006f8e;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.5em;
            margin: 0;
        }

        .user-info {
            font-size: 1.2em;
            margin-top: 10px;
            font-weight: 300;
        }

        .home-button {
            background-color: #007ba7;
            border: none;
            color: white;
            padding: 12px 20px;
            font-size: 1.1em;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: #005f74;
        }

        .valores-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .valores-header {
            background-color: #0099cc;
            color: white;
            padding: 15px;
            font-size: 1.8em;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
            letter-spacing: 1px;
        }

        .valores-text {
            font-size: 1.2em;
            color: #444;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        ul {
            margin: 20px 0;
            padding-left: 20px;
            list-style-type: disc;
        }

        .valores-examples {
            margin-top: 20px;
            background-color: #f7f7f7;
            padding: 20px;
            border-left: 5px solid #0099cc;
            border-radius: 8px;
        }

        .long-button {
            background-color: #0099cc;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            display: block;
            width: 100%;
        }

        .long-button:hover {
            background-color: #007ba7;
        }

        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .nav-button {
            background-color: #0099cc;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .nav-button i {
            margin-right: 8px;
        }

        .nav-button:hover {
            background-color: #007ba7;
        }

        footer {
            background-color: #2a3f4e;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }

        .hidden {
            display: none;
        }

        #lottie {
            max-width: 500px;
            margin: 50px auto;
        }

    </style>
</head>
<body>

    <header>
        <h1>Plan Estratégico: <?php echo htmlspecialchars($plan_name); ?></h1>
        <p class="user-info">Usuario: <?php echo htmlspecialchars($user_name); ?></p>
        <button class="home-button" onclick="window.location.href='inicio.php'">
            <i class="fas fa-home"></i> Regresar a Inicio
        </button>
    </header>

    <div class="valores-container">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-warning">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
            </div>
        <?php endif; ?>

        <div id="lottie"></div>

        <div id="buttons" class="hidden">
            <button class="long-button">
                <i class="fas fa-file-alt"></i> CÓMO ELABORAR UN PLAN ESTRATÉGICO
            </button>

            <p class="valores-text">
                El éxito de las organizaciones reside en gran parte en la capacidad que tienen sus directivos de ejecutar una estrategia más que en la calidad de la estrategia en sí.
                La planificación y asignación de recursos son fundamentales para el logro de la misma. Un Plan Estratégico es un conjunto de acciones para alinear los recursos y alcanzar el estado deseado.
            </p>

            <h2>INFORMACIÓN</h2>

            <div class="button-grid">
                <button class="nav-button" onclick="window.location.href='mision.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-bullseye"></i> 1. MISIÓN
                </button>
                <button class="nav-button" onclick="window.location.href='vision.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-eye"></i> 2. VISIÓN
                </button>
                <button class="nav-button" onclick="window.location.href='valores.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-hand-holding-heart"></i> 3. VALORES
                </button>
                <button class="nav-button" onclick="window.location.href='objetivos.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-tasks"></i> 4. OBJETIVOS
                </button>
                <button class="nav-button" onclick="window.location.href='analisis.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-chart-line"></i> 5. ANÁLISIS INTERNO Y EXTERNO
                </button>
                <button class="nav-button" onclick="window.location.href='cadena.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-sitemap"></i> 6. CADENA DE VALOR
                </button>
                <button class="nav-button" onclick="window.location.href='matriz.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-th"></i> 7. MATRIZ PARTICIPACIÓN
                </button>
                <button class="nav-button" onclick="window.location.href='porter.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-briefcase"></i> 8. LAS 5 FUERZAS DE PORTER
                </button>
                <button class="nav-button" onclick="window.location.href='pest.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-globe-americas"></i> 9. PEST
                </button>
                <button class="nav-button" onclick="window.location.href='identificacion.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-search"></i> 10. IDENTIFICACIÓN ESTRATÉGICA
                </button>
                <button class="nav-button" onclick="window.location.href='matrizcame.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-cogs"></i> 11. MATRIZ CAME
                </button>
            </div>

            <button class="long-button" onclick="window.location.href='resumen.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                <i class="fas fa-file-alt"></i> RESUMEN DEL PLAN EJECUTIVO
            </button>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js"></script>
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie'),
            renderer: 'svg',
            loop: false,
            autoplay: true,
            path: '../lottie/Cargando.json'
        });

        animation.addEventListener('complete', function() {
            document.getElementById('lottie').style.display = 'none';
            document.getElementById('buttons').classList.remove('hidden');
        });
    </script>

</body>
</html>