<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inicializar la variable de valores
$valores_usuario = "";

// Verificar si se ha recibido 'plan_id' desde la solicitud (usando $_GET en lugar de $_POST)
if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];

    // Recuperar los valores existentes del usuario y plan desde MongoDB
    try {
        $collection = $db->valores;

        // Buscar un documento donde 'user_id' y 'plan_id' coincidan
        $documento = $collection->findOne(['user_id' => $_SESSION['user_id'], 'plan_id' => $plan_id]);

        if ($documento && isset($documento['valores'])) {
            // Asignar los valores existentes a la variable
            $valores_usuario = $documento['valores'];
        }
    } catch (Exception $e) {
        // Manejar errores de conexión o consulta
        error_log("Error al recuperar valores: " . $e->getMessage());
        // Establecer un mensaje de error para el usuario
        $_SESSION['error_message'] = "Ocurrió un error al recuperar tus valores. Por favor, intenta nuevamente.";
    }
} else {
    $_SESSION['error_message'] = "No se proporcionó el plan ID.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Valores</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Incluye tu archivo CSS aquí -->
</head>
<body>
    <div class="valores-container">
        <h2 class="valores-header">3. VALORES</h2>
        <p class="valores-text">
            Los <strong>VALORES</strong> de una empresa son el conjunto de principios, reglas y aspectos culturales con los que se rige la organización.
        </p>
        <ul>
            <li>Integridad</li>
            <li>Compromiso con el desarrollo humano</li>
            <li>Ética profesional</li>
            <li>Responsabilidad social</li>
            <li>Innovación</li>
        </ul>
        
        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                    echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php
                    echo htmlspecialchars($_SESSION['error_message']);
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario que envía datos a logicaValores.php -->
        <form method="POST" action="../logica/logicaValores.php">
            <!-- Token CSRF oculto -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <!-- plan_id oculto -->
            <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($plan_id); ?>">

            <!-- Área de texto para ingresar valores, prellenada con los valores existentes -->
            <textarea name="valores" placeholder="Ingrese los valores de su empresa aquí..." required><?php echo htmlspecialchars($valores_usuario); ?></textarea>
            
            <div class="navigation-buttons">
                <button type="submit" name="action" value="index" class="nav-button">ÍNDICE</button>
                <button type="submit" name="action" value="vision" class="nav-button">2. VISIÓN</button>
                <button type="submit" name="action" value="resumen" class="nav-button">4. RESUMEN</button>
            </div>
        </form>

    </div>
</body>
</html>
