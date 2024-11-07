<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz BCG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 20px;
            width: 80%;
            max-width: 600px;
        }
        .section {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product {
            margin: 10px 0;
            font-size: 1.1em;
        }
        /* Icons for sections */
        .dog::before {
            content: "🐶";
            font-size: 2em;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .question::before {
            content: "❓";
            font-size: 2em;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .cash-cow::before {
            content: "🐄";
            font-size: 2em;
            position: absolute;
            bottom: 10px;
            left: 10px;
        }
        .star::before {
            content: "⭐";
            font-size: 2em;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sección Perro -->
        <div class="section dog">
            <div class="section-title">Perro</div>
            <?php
            $productos = [
                ["nombre" => "Producto 1", "ventas" => 50, "ganancias" => 20],
                ["nombre" => "Producto 2", "ventas" => 200, "ganancias" => 150],
                ["nombre" => "Producto 3", "ventas" => 150, "ganancias" => 30],
                ["nombre" => "Producto 4", "ventas" => 300, "ganancias" => 400],
                ["nombre" => "Producto 5", "ventas" => 500, "ganancias" => 500]
            ];
            
            foreach ($productos as $producto) {
                if ($producto['ventas'] < 100 && $producto['ganancias'] < 100) {
                    echo "<div class='product'>{$producto['nombre']} ({$producto['ventas']}%, {$producto['ganancias']}%)</div>";
                }
            }
            ?>
        </div>

        <!-- Sección Interrogación -->
        <div class="section question">
            <div class="section-title">Interrogación</div>
            <?php
            foreach ($productos as $producto) {
                if ($producto['ventas'] < 100 && $producto['ganancias'] >= 100) {
                    echo "<div class='product'>{$producto['nombre']} ({$producto['ventas']}%, {$producto['ganancias']}%)</div>";
                }
            }
            ?>
        </div>

        <!-- Sección Vaca -->
        <div class="section cash-cow">
            <div class="section-title">Vaca</div>
            <?php
            foreach ($productos as $producto) {
                if ($producto['ventas'] >= 100 && $producto['ganancias'] < 100) {
                    echo "<div class='product'>{$producto['nombre']} ({$producto['ventas']}%, {$producto['ganancias']}%)</div>";
                }
            }
            ?>
        </div>

        <!-- Sección Estrella -->
        <div class="section star">
            <div class="section-title">Estrella</div>
            <?php
            foreach ($productos as $producto) {
                if ($producto['ventas'] >= 100 && $producto['ganancias'] >= 100) {
                    echo "<div class='product'>{$producto['nombre']} ({$producto['ventas']}%, {$producto['ganancias']}%)</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
