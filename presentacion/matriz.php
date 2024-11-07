<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz de Crecimiento - Participación BCG</title>
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
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.5em;
            background-color: #0073e6;
            color: #ffffff;
            padding: 15px;
            border-radius: 5px;
        }

        .content {
            margin-top: 10px;
            line-height: 1.6;
        }

        .image-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .image-container img {
            width: 80px;
            height: auto;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #0073e6;
            color: white;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .buttons .nav-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48%;
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #0073e6;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .buttons .nav-button:hover {
            background-color: #005bb5;
        }

        footer {
            text-align: center;
            color: #888;
            font-size: 0.8em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>7. ANÁLISIS INTERNO: MATRIZ DE CRECIMIENTO - PARTICIPACIÓN BCG</h1>
        </div>

        <div class="content">
            <p>
                Toda empresa debe analizar de forma periódica su cartera de productos y servicios.
            </p>
            <p>
                La <strong>Matriz de crecimiento - participación</strong>, conocida como Matriz BCG, es un método gráfico de análisis de cartera de negocios desarrollado por The Boston Consulting Group en la década de 1970. Su finalidad es ayudar a priorizar recursos entre distintas áreas de negocios o Unidades Estratégicas de Análisis (UEA).
            </p>
            <p>
                El eje vertical de la matriz define el crecimiento en el mercado, y el horizontal la cuota de mercado.
            </p>

            <div class="image-container">
                <img src="images/incognita.png" alt="Incógnita">
                <img src="images/estrella.png" alt="Estrella">
                <img src="images/vaca.png" alt="Vaca">
                <img src="images/perro.png" alt="Perro">
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Características</th>
                        <th>Estrella</th>
                        <th>Incógnita</th>
                        <th>Vaca</th>
                        <th>Perro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cuota de mercado</td>
                        <td>Alta</td>
                        <td>Baja</td>
                        <td>Alta</td>
                        <td>Baja</td>
                    </tr>
                    <tr>
                        <td>Crecimiento del mercado</td>
                        <td>Alto</td>
                        <td>Alto</td>
                        <td>Bajo</td>
                        <td>Bajo</td>
                    </tr>
                    <tr>
                        <td>Estrategia en función de participación en mercado</td>
                        <td>Crecer o mantenerse</td>
                        <td>Crecer</td>
                        <td>Mantenerse</td>
                        <td>Abandonar</td>
                    </tr>
                    <tr>
                        <td>Inversión requerida</td>
                        <td>Muy alta</td>
                        <td>Alta</td>
                        <td>Baja</td>
                        <td>Baja</td>
                    </tr>
                    <tr>
                        <td>Rentabilidad</td>
                        <td>Alta</td>
                        <td>Baja o negativa</td>
                        <td>Alta</td>
                        <td>Baja</td>
                    </tr>
                    <tr>
                        <th>Decisión Estratégica</th>
                        <td>Potenciar</td>
                        <td>Evaluar</td>
                        <td>Mantener</td>
                        <td>Reestructurar o Desinvertir</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="buttons">
            <a href="cadena.php" class="nav-button">6. CADENA DE VALOR</a>
            <a href="bcg.php" class="nav-button">AUTODIAGNÓSTICO BCG</a>
        </div>
        
        <footer>
            <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
