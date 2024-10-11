<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3. VALORES</title>
    <link rel="stylesheet" href="estilos.css">
   <script>
        function guardarValores() {
            const valores = document.getElementById('valores-textarea').value;
            if (valores) {
                localStorage.setItem('valores', valores);
                alert('Los valores han sido guardados con éxito.');
            } else {
                alert('Por favor, escriba los valores antes de guardar.');
            }
        }
    </script>
</head>
<body>
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php'">INDICE</button>
    </div>

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

        <div class="valores-examples">
            <strong>EJEMPLOS</strong><br>
            <strong>Empresa de servicios</strong>
            <p>La excelencia en la prestación de servicios y la innovación orientada a la mejora continua.</p>
            <strong>Empresa productora de café</strong>
            <p>Nuestra labor es la búsqueda de la perfección con pasión y ética, creando valor a través de la sostenibilidad.</p>
            <strong>Agencia de certificación</strong>
            <p>Compromiso con la integridad, ética y responsabilidad social.</p>
        </div>

        <textarea id="valores-textarea" placeholder="En este apartado exponga los Valores de su empresa."></textarea>
        <button class="save-button" onclick="guardarValores()">Guardar Valores</button>
    </div>

    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='vision.html'">2. VISIÓN</button>
        <button class="nav-button" onclick="window.location.href='estrategia.html'">4. ESTRATEGIA</button>
    </div>

</body>
</html>