<?php
// valores.php
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
        
        <!-- Formulario que envía datos a guardar.php -->
        <form method="POST" action="../logica/logicaValores.php">
            <!-- Área de texto para ingresar valores -->
            <textarea name="valores" placeholder="Ingrese los valores de su empresa aquí..." required></textarea>
            
            <div class="navigation-buttons">
                <button type="submit" name="action" value="index" class="nav-button">INDICE</button>
                <button type="submit" name="action" value="vision" class="nav-button">2. VISIÓN</button>
                <button type="submit" name="action" value="resumen" class="nav-button">4. RESUMEN</button>
            </div>
        </form>
    </div>
</body>
</html>
