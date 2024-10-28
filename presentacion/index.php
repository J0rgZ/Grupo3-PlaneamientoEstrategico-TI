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
    <link rel="stylesheet" href="estilos.css"> <!-- Incluye tu archivo CSS aquí -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
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
        header {
            background-color: #2f8fa3;
            color: white;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        header h1 {
            margin: 0;
            font-size: 2.8em;
            font-weight: bold;
        }
        .user-info {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .hidden {
            display: none;
        }
        .long-button, .nav-button {
            width: 100%;
            padding: 15px;
            background-color: #2f8fa3;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        .nav-button {
            border-radius: 25px;
        }
        .long-button:hover, .nav-button:hover {
            background-color: #276f82;
            transform: scale(1.05);
        }
        .button-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .icon {
            margin-right: 10px;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #888;
        }
        .home-button {
            margin-top: 10px;
            background-color: #d9534f;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 5px;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-container">
            <h1>Plan Estratégico: <?php echo htmlspecialchars($plan_name); ?></h1>
            <p class="user-info">Usuario: <?php echo htmlspecialchars($user_name); ?></p>
            <button class="nav-button home-button" onclick="window.location.href='inicio.php'">
                <i class="fas fa-home"></i> Regresar a Inicio
            </button>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-warning">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
            </div>
        <?php endif; ?>

        <div id="lottie" style="width: 100%; height: 300px;"></div>
        
        <div id="buttons" class="hidden">
            <button class="long-button">
                <i class="fas fa-file-alt icon"></i> CÓMO ELABORAR UN PLAN ESTRATÉGICO
            </button>
            
            <p>
                El éxito de las organizaciones reside en gran parte en la capacidad que tienen sus directivos de ejecutar una estrategia más que en la calidad de la estrategia en sí.
                La planificación y asignación de recursos son fundamentales para el logro de la misma. En este sentido, un Plan Estratégico puede entenderse como el conjunto de acciones
                que han de llevarse a cabo para alinear los recursos y potencialidades con el fin de conseguir el estado deseado, es decir, la adaptación y adquisición de competitividad empresarial.
            </p>
            
            <h2>INFORMACIÓN</h2>

            <div class="button-grid">
                <button class="nav-button" onclick="window.location.href='mision.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-bullseye icon"></i> 1. MISIÓN
                </button>
                <button class="nav-button" onclick="window.location.href='vision.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-eye icon"></i> 2. VISIÓN
                </button>
                <button class="nav-button" onclick="window.location.href='valores.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-hand-holding-heart icon"></i> 3. VALORES
                </button>
                <button class="nav-button" onclick="window.location.href='objetivos.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-tasks icon"></i> 4. OBJETIVOS
                </button>
                <button class="nav-button" onclick="window.location.href='analisis.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-chart-line icon"></i> 5. ANÁLISIS INTERNO Y EXTERNO
                </button>
                <button class="nav-button" onclick="window.location.href='cadena.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-sitemap icon"></i> 6. CADENA DE VALOR
                </button>
                <button class="nav-button" onclick="window.location.href='matriz.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                    <i class="fas fa-th icon"></i> 7. MATRIZ PARTICIPACIÓN
                </button>
            </div>

            <button class="long-button" style="margin-top: 30px;" onclick="window.location.href='resumen.php?plan_id=<?php echo htmlspecialchars($id_plan); ?>'">
                <i class="fas fa-file-alt icon"></i> RESUMEN DEL PLAN EJECUTIVO
            </button>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js"></script>
    <script>
        // Inicializar Lottie para mostrar animaciones
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie'),
            renderer: 'svg',
            loop: false,
            autoplay: true,
            path: '../lottie/Cargando.json'
        });

        // Mostrar los botones después de que la animación se complete
        animation.addEventListener('complete', function() {
            document.getElementById('lottie').style.display = 'none';
            document.getElementById('buttons').classList.remove('hidden');
        });
    </script>

</body>
</html>
