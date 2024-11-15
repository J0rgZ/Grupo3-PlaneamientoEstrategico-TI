<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Competitiva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .contenedor {
            width: 80%; /* Puedes ajustar este valor */
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Permite desplazarse si el contenido es muy largo */
            height: 90vh; /* Ajusta este valor si deseas cambiar la altura */
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #555;
        }
        td {
            font-size: 14px;
        }
        .titulo {
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
        .button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
        .text-label {
            font-size: 14px;
            color: #777;
        }
        .resultado, .conclusiones {
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .resultado {
            margin-bottom: 20px;
        }
        .conclusiones {
            width: 60%;
            float: left;
        }
        .resultados-box {
            width: 30%;
            float: right;
            background-color: #eaf7e1;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .resultados-box h3 {
            text-align: center;
            color: #333;
        }
        .opciones-container {
            margin-top: 30px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .opciones-container h3 {
            color: #333;
            font-size: 20px;
            font-weight: bold;
        }
        .opciones-container label {
            font-size: 14px;
            color: #555;
        }
        .opciones-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
<div class="contenedor">

<h1>Evaluación Competitiva del Mercado</h1>

<form action="">

    <!-- Rivalidad entre Empresas del Sector -->
    <div class="titulo">Rivalidad entre Empresas del Sector</div>
    <table>
        <tr>
            <th>Factor</th>
            <th>Hostil</th>
            <th>Nada</th>
            <th>Poco</th>
            <th>Medio</th>
            <th>Alto</th>
            <th>Muy Alto</th>
            <th>Favorable</th>
        </tr>
        <tr>
            <td>Crecimiento</td>
            <td><span class="text-label">Lento</span></td>
            <td><input type="radio" name="crecimiento" value="1"></td>
            <td><input type="radio" name="crecimiento" value="2"></td>
            <td><input type="radio" name="crecimiento" value="3"></td>
            <td><input type="radio" name="crecimiento" value="4"></td>
            <td><input type="radio" name="crecimiento" value="5"></td>
            <td><span class="text-label">Rápido</span></td>
        </tr>
        <tr>
            <td>Naturaleza de los Competidores</td>
            <td><span class="text-label">Muchos</span></td>
            <td><input type="radio" name="competidores" value="1"></td>
            <td><input type="radio" name="competidores" value="2"></td>
            <td><input type="radio" name="competidores" value="3"></td>
            <td><input type="radio" name="competidores" value="4"></td>
            <td><input type="radio" name="competidores" value="5"></td>
            <td><span class="text-label">Pocos</span></td>
        </tr>
        <tr>
            <td>Exceso de Capacidad Productiva</td>
            <td><span class="text-label">Si</span></td>
            <td><input type="radio" name="exceso" value="1"></td>
            <td><input type="radio" name="exceso" value="2"></td>
            <td><input type="radio" name="exceso" value="3"></td>
            <td><input type="radio" name="exceso" value="4"></td>
            <td><input type="radio" name="exceso" value="5"></td>
            <td><span class="text-label">No</span></td>
        </tr>
        <tr>
            <td>Rentabilidad Media del Sector</td>
            <td><span class="text-label">Baja</span></td>
            <td><input type="radio" name="rentabilidad" value="1"></td>
            <td><input type="radio" name="rentabilidad" value="2"></td>
            <td><input type="radio" name="rentabilidad" value="3"></td>
            <td><input type="radio" name="rentabilidad" value="4"></td>
            <td><input type="radio" name="rentabilidad" value="5"></td>
            <td><span class="text-label">Alta</span></td>
        </tr>
        <tr>
            <td>Diferenciación del Producto</td>
            <td><span class="text-label">Escasa</span></td>
            <td><input type="radio" name="diferenciacion" value="1"></td>
            <td><input type="radio" name="diferenciacion" value="2"></td>
            <td><input type="radio" name="diferenciacion" value="3"></td>
            <td><input type="radio" name="diferenciacion" value="4"></td>
            <td><input type="radio" name="diferenciacion" value="5"></td>
            <td><span class="text-label">Elevada</span></td>
        </tr>
        <tr>
            <td>Barreras de Salida</td>
            <td><span class="text-label">Bajas</span></td>
            <td><input type="radio" name="barreras_salida" value="1"></td>
            <td><input type="radio" name="barreras_salida" value="2"></td>
            <td><input type="radio" name="barreras_salida" value="3"></td>
            <td><input type="radio" name="barreras_salida" value="4"></td>
            <td><input type="radio" name="barreras_salida" value="5"></td>
            <td><span class="text-label">Altas</span></td>
        </tr>
    </table>

    <!-- Barreras de Entrada -->
    <div class="titulo">Barreras de Entrada</div>
    <table>
        <tr>
            <th>Factor</th>
            <th>Hostil</th>
            <th>Nada</th>
            <th>Poco</th>
            <th>Medio</th>
            <th>Alto</th>
            <th>Muy Alto</th>
            <th>Favorable</th>
        </tr>
        <tr>
            <td>Economías de Escala</td>
            <td><span class="text-label">No</span></td>
            <td><input type="radio" name="economias" value="1"></td>
            <td><input type="radio" name="economias" value="2"></td>
            <td><input type="radio" name="economias" value="3"></td>
            <td><input type="radio" name="economias" value="4"></td>
            <td><input type="radio" name="economias" value="5"></td>
            <td><span class="text-label">Si</span></td>
        </tr>
        <tr>
            <td>Necesidad de Capital</td>
            <td><span class="text-label">Bajas</span></td>
            <td><input type="radio" name="capital" value="1"></td>
            <td><input type="radio" name="capital" value="2"></td>
            <td><input type="radio" name="capital" value="3"></td>
            <td><input type="radio" name="capital" value="4"></td>
            <td><input type="radio" name="capital" value="5"></td>
            <td><span class="text-label">Altas</span></td>
        </tr>
        <tr>
            <td>Acceso a la Tecnología</td>
            <td><span class="text-label">Facil</span></td>
            <td><input type="radio" name="tecnologia" value="1"></td>
            <td><input type="radio" name="tecnologia" value="2"></td>
            <td><input type="radio" name="tecnologia" value="3"></td>
            <td><input type="radio" name="tecnologia" value="4"></td>
            <td><input type="radio" name="tecnologia" value="5"></td>
            <td><span class="text-label">Dificil</span></td>
        </tr>
        <tr>
            <td>Reglamentos o Leyes Limitativos</td>
            <td><span class="text-label">No</span></td>
            <td><input type="radio" name="reglamentos" value="1"></td>
            <td><input type="radio" name="reglamentos" value="2"></td>
            <td><input type="radio" name="reglamentos" value="3"></td>
            <td><input type="radio" name="reglamentos" value="4"></td>
            <td><input type="radio" name="reglamentos" value="5"></td>
            <td><span class="text-label">Si</span></td>
        </tr>
        <tr>
            <td>Tramites burocraticos</td>
            <td><span class="text-label">No</span></td>
            <td><input type="radio" name="burocraticos" value="1"></td>
            <td><input type="radio" name="burocraticos" value="2"></td>
            <td><input type="radio" name="burocraticos" value="3"></td>
            <td><input type="radio" name="burocraticos" value="4"></td>
            <td><input type="radio" name="burocraticos" value="5"></td>
            <td><span class="text-label">Si</span></td>
        </tr>
        <tr>
            <td>Reaccion esperada actuales competidores</td>
            <td><span class="text-label">Escasa</span></td>
            <td><input type="radio" name="Reaccion" value="1"></td>
            <td><input type="radio" name="Reaccion" value="2"></td>
            <td><input type="radio" name="Reaccion" value="3"></td>
            <td><input type="radio" name="Reaccion" value="4"></td>
            <td><input type="radio" name="Reaccion" value="5"></td>
            <td><span class="text-label">Energica</span></td>
        </tr>
    </table>

    <!-- Poder de los Clientes -->
    <div class="titulo">Poder de los Clientes</div>
    <table>
        <tr>
            <th>Factor</th>
            <th>Hostil</th>
            <th>Nada</th>
            <th>Poco</th>
            <th>Medio</th>
            <th>Alto</th>
            <th>Muy Alto</th>
            <th>Favorable</th>
        </tr>
        <tr>
            <td>Numero de Clientes</td>
            <td><span class="text-label">Pocos</span></td>
            <td><input type="radio" name="numero_clientes" value="1"></td>
            <td><input type="radio" name="numero_clientes" value="2"></td>
            <td><input type="radio" name="numero_clientes" value="3"></td>
            <td><input type="radio" name="numero_clientes" value="4"></td>
            <td><input type="radio" name="numero_clientes" value="5"></td>
            <td><span class="text-label">Muchos</span></td>
        </tr>
        <tr>
            <td>Posibilidad de Integración Ascendente</td>
            <td><span class="text-label">Pequeña</span></td>
            <td><input type="radio" name="integracion" value="1"></td>
            <td><input type="radio" name="integracion" value="2"></td>
            <td><input type="radio" name="integracion" value="3"></td>
            <td><input type="radio" name="integracion" value="4"></td>
            <td><input type="radio" name="integracion" value="5"></td>
            <td><span class="text-label">Grande</span></td>
        </tr>
        <tr>
            <td>Rentabilidad de los Clientes</td>
            <td><span class="text-label">Baja</span></td>
            <td><input type="radio" name="rentabilidad_clientes" value="1"></td>
            <td><input type="radio" name="rentabilidad_clientes" value="2"></td>
            <td><input type="radio" name="rentabilidad_clientes" value="3"></td>
            <td><input type="radio" name="rentabilidad_clientes" value="4"></td>
            <td><input type="radio" name="rentabilidad_clientes" value="5"></td>
            <td><span class="text-label">Alta</span></td>
        </tr>
        <tr>
            <td>Coste de Cambio de Proveedor</td>
            <td><span class="text-label">Bajo</span></td>
            <td><input type="radio" name="coste_cambio" value="1"></td>
            <td><input type="radio" name="coste_cambio" value="2"></td>
            <td><input type="radio" name="coste_cambio" value="3"></td>
            <td><input type="radio" name="coste_cambio" value="4"></td>
            <td><input type="radio" name="coste_cambio" value="5"></td>
            <td><span class="text-label">Alto</span></td>
        </tr>
    </table>

    <!-- Productos Sustitutivos -->
    <div class="titulo">Productos Sustitutivos</div>
    <table>
        <tr>
            <th>Factor</th>
            <th>Hostil</th>
            <th>Nada</th>
            <th>Poco</th>
            <th>Medio</th>
            <th>Alto</th>
            <th>Muy Alto</th>
            <th>Favorable</th>
        </tr>
        <tr>
            <td>Disponibilidad de Productos Sustitutivos</td>
            <td><span class="text-label">Grande</span></td>
            <td><input type="radio" name="sustitutivos" value="1"></td>
            <td><input type="radio" name="sustitutivos" value="2"></td>
            <td><input type="radio" name="sustitutivos" value="3"></td>
            <td><input type="radio" name="sustitutivos" value="4"></td>
            <td><input type="radio" name="sustitutivos" value="5"></td>
            <td><span class="text-label">Pequeña</span></td>
        </tr>
    </table>

    <button type="button" class="button" onclick="evaluarCompetencia()">Evaluar Competencia</button>

    </form>

<div class="clear"></div>

<div class="resultado">
    <h2>Resultado de la Evaluación</h2>
    <p><strong>Puntuación total:</strong> <span id="puntuacion-total">0</span></p>
</div>

<div class="conclusiones">
    <h3>Conclusiones:</h3>
    <p id="texto-conclusiones">Selecciona los valores para ver las conclusiones aquí.</p>
</div>

<div class="resultados-box">
    <h3>Resultados Detallados</h3>
    <p id="resultado-detalle">Aquí se mostrarán los detalles según tu evaluación.</p>
</div>

<div class="clear"></div>

<!-- Espacio para Oportunidades y Amenazas -->
<div class="opciones-container">
    <h3>Oportunidades y Amenazas</h3>
    <div>
        <label for="oportunidad1">Oportunidad 1:</label>
        <input type="text" id="oportunidad1" placeholder="Escribe tu oportunidad aquí">
    </div>
    <div>
        <label for="oportunidad2">Oportunidad 2:</label>
        <input type="text" id="oportunidad2" placeholder="Escribe otra oportunidad aquí">
    </div>
    <div>
        <label for="amenaza1">Amenaza 1:</label>
        <input type="text" id="amenaza1" placeholder="Escribe tu amenaza aquí">
    </div>
    <div>
        <label for="amenaza2">Amenaza 2:</label>
        <input type="text" id="amenaza2" placeholder="Escribe otra amenaza aquí">
    </div>
</div>

<script>
function evaluarCompetencia() {
    let puntuacionTotal = 0;

    // Array con los nombres de los factores a evaluar
    const evaluacion = [
        'crecimiento', 'competidores', 'exceso', 'rentabilidad', 'diferenciacion',
        'barreras_salida', 'economias', 'capital', 'tecnologia', 'reglamentos',
        'numero_clientes', 'integracion', 'rentabilidad_clientes', 'coste_cambio', 'sustitutivos'
    ];

    // Iterar sobre cada factor para sumar las puntuaciones seleccionadas
    evaluacion.forEach(factor => {
        const seleccion = document.querySelector(`input[name="${factor}"]:checked`);
        if (seleccion) {
            puntuacionTotal += parseInt(seleccion.value);
        }
    });

    // Mostrar la puntuación total
    document.getElementById("puntuacion-total").textContent = puntuacionTotal;

    // Mostrar conclusiones según la puntuación total
    let conclusiones = "";
    if (puntuacionTotal <= 10) {
        conclusiones = "La competencia en este mercado es baja. Las oportunidades para entrar al mercado son favorables.";
    } else if (puntuacionTotal <= 20) {
        conclusiones = "La competencia en este mercado es moderada. Se requiere una buena estrategia para competir.";
    } else {
        conclusiones = "La competencia en este mercado es alta. Es un mercado muy competitivo y difícil de ingresar.";
    }

    document.getElementById("texto-conclusiones").textContent = conclusiones;

    // Mostrar detalles de la evaluación
    const detalle = `Tu evaluación ha dado una puntuación total de ${puntuacionTotal}. Revisa tus respuestas para comprender mejor la situación competitiva del mercado.`;
    document.getElementById("resultado-detalle").textContent = detalle;
}
</script>
</div>

</body>
</html>