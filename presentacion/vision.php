<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión Empresarial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .indice {
            background-color: #ff7f7f;
            color: white;
            padding: 5px 10px;
            display: inline-block;
            margin-bottom: 10px;
            font-weight: bold;
            text-decoration: none;
        }
        h1 {
            background-color: #4ca1af;
            color: white;
            padding: 10px;
            margin-top: 0;
            text-align: center;
        }
        ul {
            padding-left: 20px;
            margin-bottom: 10px;
        }
        .vision-input {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 10px;
            position: relative;
        }
        .vision-input textarea {
            width: 100%;
            padding: 5px;
            border: none;
            resize: vertical;
        }
        .vision-input::before {
            content: "En este apartado describa la Visión de su empresa.";
            display: block;
            background-color: #e0e0e0;
            padding: 5px;
            margin: -10px -10px 10px -10px;
        }
        .pencil-icon {
            position: absolute;
            top: 10px;
            left: -30px;
            font-size: 24px;
        }
        .diagram {
            margin-top: 20px;
            text-align: center;
        }
        .diagram-circle {
            display: inline-block;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #808000;
            color: white;
            line-height: 100px;
            text-align: center;
            margin: 0 20px;
        }
        .diagram-arrow {
            display: inline-block;
            background-color: #4ca1af;
            color: white;
            padding: 10px;
            margin: 0 -5px;
            position: relative;
            top: -40px;
        }
        .diagram-arrow::after {
            content: '';
            position: absolute;
            right: -20px;
            top: 0;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            border-left: 20px solid #4ca1af;
        }
        .diagram-questions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
        .nav-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4ca1af;
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .nav-arrow {
            font-size: 24px;
            color: #4ca1af;
            text-decoration: none;
        }
        .examples {
            background-color: #f9f9f9;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <a href="index.php" class="indice">INDICE</a>
        </header>
        
        <main>
            <h1>2. VISIÓN</h1>
            
            <p>La <strong>VISIÓN</strong> de una empresa define lo que la empresa/organización quiere lograr en el futuro. Es lo que la organización aspira llegar a ser en torno a 2-3 años.</p>
            
            <ul>
                <li>Debe ser retadora, positiva, compartida y coherente con la misión.</li>
                <li>Marca el fin último que la estrategia debe seguir.</li>
                <li>Proyecta la imagen de destino que se pretende alcanzar.</li>
            </ul>
            
            <p>La visión debe ser conocida y compartida por todos los miembros de la empresa y también por aquellos que se relacionan con ella.</p>
            
            <div class="examples">
                <h2>EJEMPLOS</h2>
                <p><strong>Empresa de servicios</strong><br>
                Ser el grupo empresarial de referencia en nuestras áreas de actividad</p>
                
                <p><strong>Empresa productora de café</strong><br>
                Queremos ser en el mundo el punto de referencia de la cultura y de la excelencia del café. Una empresa innovadora que propone los mejores productos y lugares de consumo y que, gracias a ello, crece y se convierte en líder de la alta gama.</p>
                
                <p><strong>Agencia de certificación</strong><br>
                Ser líderes en nuestro sector y un actor principal en todos los segmentos de mercado en los que estamos presentes, en los mercados clave.</p>
            </div>
            
            <div class="vision-input">
                <span class="pencil-icon">✎</span>
                <textarea rows="4" cols="50"></textarea>
            </div>
            
            <div class="diagram">
                <p><strong>Relación entre Misión y Visión</strong></p>
                <div class="diagram-circle">Misión</div>
                <div class="diagram-arrow">Procesos cotidianos</div>
                <div class="diagram-circle">Visión</div>
                <div class="diagram-questions">
                    <span>¿Cuál es la situación actual?</span>
                    <span>¿Qué camino a seguir?</span>
                    <span>¿Cuál es la situación futura?</span>
                </div>
            </div>
        </main>
        
        <footer>
            <a href="mision.php" class="nav-arrow">&#9664;</a>
            <a href="mision.php" class="nav-button">1. MISIÓN</a>
            <a href="valores.php" class="nav-button">3. VALORES</a>
            <a href="valores.php" class="nav-arrow">&#9654;</a>
        </footer>
    </div>
</body>
</html>