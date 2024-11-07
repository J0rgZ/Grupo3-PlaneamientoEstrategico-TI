<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico BCG</title>
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

        .form-section {
            text-align: left;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        .add-product-button, .submit-button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #0073e6;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .submit-button:hover, .add-product-button:hover {
            background-color: #005bb5;
        }

        .product-container {
            margin-top: 20px;
        }

        footer {
            text-align: center;
            color: #888;
            font-size: 0.8em;
            margin-top: 20px;
        }
    </style>
    <script>
        function addProductField() {
            const container = document.getElementById('product-container');
            const productDiv = document.createElement('div');
            productDiv.classList.add('form-group');

            const label = document.createElement('label');
            label.textContent = 'Producto:';
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'productos[]';
            input.required = true;

            productDiv.appendChild(label);
            productDiv.appendChild(input);
            container.appendChild(productDiv);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Autodiagnóstico BCG</h1>
        </div>

        <div class="form-section">
            <form action="resultado.php" method="post">
                <div class="form-group">
                    <label for="empresa">Nombre de la Empresa:</label>
                    <input type="text" id="empresa" name="empresa" required>
                </div>

                <div id="product-container">
                    <div class="form-group">
                        <label for="productos[]">Producto:</label>
                        <input type="text" id="producto" name="productos[]" required>
                    </div>
                </div>

                <button type="button" class="add-product-button" onclick="addProductField()">Añadir otro producto</button>

                <div class="form-group">
                    <label for="cuotaMercado">Cuota de Mercado (alta/baja):</label>
                    <select id="cuotaMercado" name="cuotaMercado" required>
                        <option value="">Seleccione una opción</option>
                        <option value="alta">Alta</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="crecimientoMercado">Crecimiento del Mercado (alto/bajo):</label>
                    <select id="crecimientoMercado" name="crecimientoMercado" required>
                        <option value="">Seleccione una opción</option>
                        <option value="alto">Alto</option>
                        <option value="bajo">Bajo</option>
                    </select>
                </div>

                <button type="submit" class="submit-button">Evaluar</button>
            </form>
        </div>
        
        <footer>
            <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
