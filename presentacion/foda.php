<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posicionamiento BCG y FODA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            background-color: #0073e6;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.2em;
        }

        .positioning-chart {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin: 30px 0;
            position: relative;
            height: 200px;
            border-top: 2px solid #333;
            border-right: 2px solid #333;
        }

        .bubble {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            font-size: 1em;
        }

        .chart-label {
            position: absolute;
            font-size: 0.9em;
            color: #333;
        }

        .fortalezas-debilidades {
            margin: 30px 0;
            text-align: left;
            font-size: 1em;
        }

        .fortalezas-debilidades h3 {
            background-color: #f0f0f0;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 10px;
            color: #0073e6;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .nav-button {
            background-color: #0073e6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .nav-button:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Posicionamiento BCG y Análisis FODA</h2>

        <!-- Posicionamiento de Productos -->
        <div class="positioning-chart">
            <!-- Representación de productos con burbujas y porcentajes simulados -->
            <div class="bubble" style="background-color: #3498db; left: 10%;">100%</div>
            <div class="bubble" style="background-color: #f1c40f; left: 30%;">200%</div>
            <div class="bubble" style="background-color: #e74c3c; left: 50%;">300%</div>
            <div class="bubble" style="background-color: #2ecc71; left: 70%;">400%</div>
            <div class="bubble" style="background-color: #e67e22; left: 90%;">500%</div>

            <!-- Etiquetas del gráfico -->
            <span class="chart-label" style="top: -20px; left: 0;">Incógnita</span>
            <span class="chart-label" style="top: -20px; right: 0;">Estrella</span>
            <span class="chart-label" style="bottom: -20px; left: 0;">Perro</span>
            <span class="chart-label" style="bottom: -20px; right: 0;">Vaca</span>
        </div>

        <p>Cómo puede observar, cada producto y/o servicio, representado a través de una bola y color tiene un posicionamiento determinado</p>

        <!-- Reflexión General FODA -->
        <div class="fortalezas-debilidades">
            <p><em>Realice una reflexión general sobre sus productos y servicios e identifique las fortalezas y amenazas más significativas de su empresa. La información aportada servirá para completar la matriz FODA.</em></p>

            <!-- Fortalezas -->
            <h3>FORTALEZAS</h3>
            <p>F3: <input type="text" name="fortaleza_f3" placeholder="Describa una fortaleza"></p>
            <p>F4: <input type="text" name="fortaleza_f4" placeholder="Describa otra fortaleza"></p>

            <!-- Debilidades -->
            <h3>DEBILIDADES</h3>
            <p>D3: <input type="text" name="debilidad_d3" placeholder="Describa una debilidad"></p>
            <p>D4: <input type="text" name="debilidad_d4" placeholder="Describa otra debilidad"></p>
        </div>

        <!-- Navegación -->
        <div class="nav-buttons">
            <a href="bcg.php" class="nav-button">7. BCG</a>
            <a href="porter.php" class="nav-button">8. ANÁLISIS PORTER</a>
        </div>
    </div>
</body>
</html>
