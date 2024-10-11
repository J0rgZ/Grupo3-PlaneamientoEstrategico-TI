<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3. VALORES</title>
    <link rel="stylesheet" href="estilos.css">
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

        <!-- Formulario para enviar los valores -->
        <form method="POST" action="../datos/conexion.php">
            <textarea id="valores-textarea" name="valores" placeholder="En este apartado exponga los Valores de su empresa." required></textarea>
            <button type="submit" class="save-button">Guardar Valores</button>
        </form>
    </div>

    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='vision.html'">2. VISIÓN</button>
        <button class="nav-button" onclick="window.location.href='estrategia.html'">4. ESTRATEGIA</button>
    </div>

</body>
</html>
