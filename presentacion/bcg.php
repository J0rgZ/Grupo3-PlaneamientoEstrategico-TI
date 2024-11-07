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
            max-width: 1200px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            background-color: #0073e6;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.2em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #0073e6;
            color: white;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-align: left;
            margin: 10px 0;
            color: #333;
        }

        .input-cell {
            width: 100px;
            text-align: right;
        }

        .highlight {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        footer {
            text-align: center;
            color: #888;
            font-size: 0.8em;
            margin-top: 20px;
        }
    </style>
    <script>
        function calculateTotals() {
            let totalSales = 0;
            for (let i = 1; i <= 5; i++) {
                const salesValue = parseFloat(document.getElementById(`ventas_producto_${i}`).value) || 0;
                totalSales += salesValue;
            }

            for (let i = 1; i <= 5; i++) {
                const salesValue = parseFloat(document.getElementById(`ventas_producto_${i}`).value) || 0;
                const percentage = totalSales > 0 ? ((salesValue / totalSales) * 100).toFixed(2) : 0;
                document.getElementById(`porcentaje_producto_${i}`).value = percentage + '%';
            }

            document.getElementById('total_sales').textContent = totalSales.toFixed(2);
            document.getElementById('total_percentage').textContent = totalSales > 0 ? "100.00%" : "0.00%";
        }

        function updateProductNames() {
            for (let i = 1; i <= 5; i++) {
                const productName = document.getElementById(`producto_nombre_${i}`).value;
                document.querySelectorAll(`.producto_${i}_nombre`).forEach(element => {
                    element.textContent = productName;
                });
            }
        }

        function updateYears(section) {
            const startYear = parseInt(document.getElementById(`${section}_start_year`).value) || 0;
            for (let i = 0; i < 5; i++) {
                document.getElementById(`${section}_year_${i}`).value = startYear + i;
                if (section === 'tcm') {
                    document.getElementById(`demanda_year_${i}`).value = startYear + i;
                }
            }
        }

        function calculateBCG() {
            // Calculate TCM as the average of each column in the TCM table
            for (let i = 1; i <= 5; i++) {
                let totalTCM = 0;
                for (let j = 0; j < 5; j++) {
                    const value = parseFloat(document.querySelector(`input[name="tcm_${j}_producto_${i}"]`).value) || 0;
                    totalTCM += value;
                }
                const averageTCM = (totalTCM / 5).toFixed(2);
                document.getElementById(`bcg_tcm_producto_${i}`).value = averageTCM + '%';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Autodiagnóstico BCG</h2>

        <!-- Previsión de Ventas -->
        <p class="section-title">Previsión de Ventas</p>
        <table>
            <tr>
                <th>PRODUCTOS</th>
                <th>VENTAS</th>
                <th>% S/ TOTAL</th>
            </tr>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr>
                    <td><input type="text" id="producto_nombre_<?= $i ?>" name="producto_nombre_<?= $i ?>" value="Producto <?= $i ?>" oninput="updateProductNames()"></td>
                    <td><input type="number" class="input-cell" id="ventas_producto_<?= $i ?>" name="ventas_producto_<?= $i ?>" oninput="calculateTotals()" placeholder="0.00"></td>
                    <td><input type="text" class="input-cell" id="porcentaje_producto_<?= $i ?>" readonly placeholder="0.00%"></td>
                </tr>
            <?php endfor; ?>
            <tr class="highlight">
                <td>TOTAL</td>
                <td id="total_sales">0</td>
                <td id="total_percentage">0.00%</td>
            </tr>
        </table>

        <!-- Tasas de Crecimiento del Mercado (TCM) -->
        <p class="section-title">Tasas de Crecimiento del Mercado (TCM)</p>
        <label for="tcm_start_year">Año de inicio:</label>
        <input type="number" id="tcm_start_year" value="2012" oninput="updateYears('tcm')">
        <table>
            <tr>
                <th>PERIODOS</th>
                <th class="producto_1_nombre">Producto 1</th>
                <th class="producto_2_nombre">Producto 2</th>
                <th class="producto_3_nombre">Producto 3</th>
                <th class="producto_4_nombre">Producto 4</th>
                <th class="producto_5_nombre">Producto 5</th>
            </tr>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <tr>
                    <td><input type="number" id="tcm_year_<?= $i ?>" value="<?= 2012 + $i ?>" class="input-cell"></td>
                    <?php for ($j = 1; $j <= 5; $j++): ?>
                        <td><input type="number" class="input-cell" name="tcm_<?= $i ?>_producto_<?= $j ?>" step="0.01" placeholder="0.00%" oninput="calculateBCG()"></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>

        <!-- BCG Analysis Table -->
        <p class="section-title">BCG</p>
        <table>
            <tr>
                <th></th>
                <th class="producto_1_nombre">Producto 1</th>
                <th class="producto_2_nombre">Producto 2</th>
                <th class="producto_3_nombre">Producto 3</th>
                <th class="producto_4_nombre">Producto 4</th>
                <th class="producto_5_nombre">Producto 5</th>
            </tr>
            <tr>
                <th>TCM</th>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <td><input type="text" id="bcg_tcm_producto_<?= $i ?>" class="input-cell" readonly placeholder="0.00%"></td>
                <?php endfor; ?>
            </tr>
            <tr>
                <th>PRM</th>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <td><input type="number" class="input-cell" name="prm_producto_<?= $i ?>" step="0.01" placeholder="0.00"></td>
                <?php endfor; ?>
            </tr>
            <tr>
                <th>% S/VTAS</th>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <td><input type="number" class="input-cell" name="ventas_percent_producto_<?= $i ?>" step="0.01" placeholder="0%"></td>
                <?php endfor; ?>
            </tr>
        </table>

                <!-- Evolución de la Demanda Global Sector -->
                <p class="section-title">Evolución de la Demanda Global Sector (en miles de soles)</p>
        <label for="demanda_start_year">Año de inicio:</label>
        <input type="number" id="demanda_start_year" value="2012" oninput="updateYears('demanda')">
        <table>
            <tr>
                <th>AÑOS</th>
                <th class="producto_1_nombre">Producto 1</th>
                <th class="producto_2_nombre">Producto 2</th>
                <th class="producto_3_nombre">Producto 3</th>
                <th class="producto_4_nombre">Producto 4</th>
                <th class="producto_5_nombre">Producto 5</th>
            </tr>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <tr>
                    <td><input type="number" id="demanda_year_<?= $i ?>" value="<?= 2012 + $i ?>" class="input-cell"></td>
                    <?php for ($j = 1; $j <= 5; $j++): ?>
                        <td><input type="number" class="input-cell" name="demanda_<?= $i ?>_producto_<?= $j ?>" placeholder="0"></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        
        <!-- Niveles de Venta de los Competidores de Cada Producto -->
        <p class="section-title">Niveles de Venta de los Competidores de Cada Producto</p>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <table>
                <tr>
                    <th colspan="2" class="producto_<?= $i ?>_nombre">Producto <?= $i ?></th>
                </tr>
                <tr>
                    <th>Competidor</th>
                    <th>Ventas</th>
                </tr>
                <?php for ($j = 1; $j <= 9; $j++): ?>
                    <tr>
                        <td>CP<?= $j ?>-<?= $i ?></td>
                        <td><input type="number" class="input-cell" name="competidor_<?= $i ?>_ventas_<?= $j ?>" placeholder="0"></td>
                    </tr>
                <?php endfor; ?>
                <tr class="highlight">
                    <td>Mayor</td>
                    <td><input type="number" class="input-cell" name="competidor_<?= $i ?>_mayor" readonly placeholder="0"></td>
                </tr>
            </table>
        <?php endfor; ?>
        <div style="text-align: center; margin-top: 20px;">
            <a href="foda.php" class="nav-button" style="padding: 10px 20px; background-color: #0073e6; color: white; text-decoration: none; border-radius: 5px;">Ir a FODA</a>
        </div>
    </div>
        <footer>
            <p>&copy; 2024 Plan Estratégico. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>

