<?php
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirige a la página de inicio de sesión
    exit();
}
// Verifica si el usuario está logeado (por ejemplo, a través de una sesión)
if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

$user_id = $_SESSION['user_id'];
$collection = $db->diagnosticos;

// Obtener los datos guardados del usuario
$user_diagnostico = $collection->findOne(['user_id' => $user_id]);

// Valores de valoraciones, fortalezas y debilidades
$valores = $user_diagnostico['valores'] ?? array_fill(0, 25, 0); // 25 preguntas
$fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
$debilidades = (array)($user_diagnostico['debilidades'] ?? array_fill(0, 4, ''));
$valores = (array)$user_diagnostico['valores'] ?? array_fill(0, 25, 0);


// Cálculo inicial del porcentaje de mejora
$suma_total = array_sum($valores);
$max_valor = 100; // El máximo es 100 si todos los valores fueran 4
$porcentaje_mejora = 1 - ($suma_total / $max_valor);
$porcentaje_mejora = round($porcentaje_mejora * 100, 2); // Convertir a porcentaje y redondear
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico de la Cadena de Valor Interna</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            padding: 20px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #00bcd4;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 28px;
            color: #00bcd4;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }

        .card {
            background-color: #1e1e1e;
            border: none;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: #292b2c;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
        }

        .table {
            background-color: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
            border: none;
            color: #fff;
        }

        .table th {
            background-color: #00bcd4;
            position: sticky;
            top: 0;
        }

        .table tbody tr:hover {
            background-color: #292b2c;
        }

        .btn-primary {
            background-color: #00bcd4;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0288d1;
        }

        .potencial-container {
    background-color: #1e1e1e; /* Fondo oscuro */
    color: #00bcd4; /* Color de texto turquesa */
    border: 2px solid #00bcd4; /* Borde turquesa */
    border-radius: 10px; /* Bordes redondeados */
    padding: 15px; /* Espaciado interno */
    margin: 20px 0; /* Espaciado externo */
    font-size: 20px; /* Tamaño de fuente */
    text-align: center; /* Alinear texto al centro */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Sombra del cuadro */
}

.porcentaje {
    font-weight: bold; /* Negrita para el porcentaje */
    font-size: 24px; /* Tamaño de fuente del porcentaje */
}
.section-title {
    font-size: 24px; /* Tamaño de fuente del título */
    color: #00bcd4; /* Color turquesa */
    margin: 20px 0 10px; /* Espaciado del título */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Sombra para el texto */
}

.styled-table {
    width: 100%; /* Ancho completo */
    border-collapse: collapse; /* Colapsar bordes */
    margin-bottom: 30px; /* Espaciado entre tablas */
}

.styled-table td {
    padding: 10px; /* Espaciado interno de celdas */
    border: 1px solid #292b2c; /* Borde de celdas */
    color: #ffffff; /* Color de texto */
}

.styled-table tr:nth-child(even) {
    background-color: #1e1e1e; /* Fondo alterno para filas */
}

.styled-table tr:hover {
    background-color: #292b2c; /* Fondo al pasar el mouse */
}

.label {
    font-weight: bold; /* Negrita para etiquetas */
}

.input-field {
    width: 100%; /* Ancho completo del campo de texto */
    padding: 8px; /* Espaciado interno del campo */
    border: 1px solid #00bcd4; /* Borde turquesa */
    border-radius: 5px; /* Bordes redondeados */
    background-color: #292b2c; /* Fondo del campo */
    color: #ffffff; /* Color de texto en el campo */
}

.input-field:focus {
    border-color: #00bcd4; /* Borde turquesa al enfocar */
    outline: none; /* Quitar el borde por defecto */
    background-color: #343a40; /* Fondo más claro al enfocar */
}

    </style>
    <script>
        // Función para guardar automáticamente los valores y recalcular el porcentaje
        function guardarValor(tipo, indice, valor) {
            // Enviar los datos al servidor usando Fetch API
            fetch('../logica/guardar_valor.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tipo: tipo,
                    indice: indice,
                    valor: valor
                })
            })
            .then(response => response.json()) // Convertir la respuesta a JSON
            .then(data => {
                if (data.success) {
                    // Actualizar el porcentaje de mejora en la página
                    document.getElementById('potencial').innerText = 'Potencial de Mejora de la Cadena de Valor Interna: ' + data.porcentaje_mejora + '%';
                } else {
                    console.error('Error al guardar los datos:', data.error);
                }
            })
            .catch(error => {
                console.error('Error al guardar automáticamente:', error);
            });
        }


        // Asignar el evento de cambio para recalcular el porcentaje al cambiar la valoración
        function calcularPorcentajeInicial() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const indice = this.name.replace('valor_', '');
                    guardarValor('valoracion', indice, this.value);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', calcularPorcentajeInicial);
    </script>
</head>
<body>


    <div class="container mt-4">
        <!-- Encabezado con botones de retroceso y cierre de sesión -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Botón para regresar -->
            <a href="index.php" class="btn btn-primary">
                ← Regresar
            </a>

            <!-- Título centrado -->
            <h2 class="text-center flex-grow-1" style="margin: 0;">
                Diagnóstico de la Cadena de Valor Interna
            </h2>

            <!-- Botón de Cerrar Sesión -->
            <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>


    <form id="diagnosticoForm">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Diagnóstico</th>
                    <th>Valoración</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $preguntas = [
                "La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.",
                "La empresa amplía los medios productivos tecnológicamente más avanzados de su sector.",
                "La empresa dispone de un sistema de información y control de gestión eficiente y eficaz.",
                "Los medios técnicos y tecnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.",
                "La empresa es un referente en su sector en I+D+i.",
                "La excelencia de los procedimientos de la empresa (en ISO, etc.) son una principal fuente de ventaja competitiva.",
                "La empresa dispone de página web, y esta se emplea no solo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.",
                "Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.",
                "La empresa es referente en su sector en la optimización, en términos de coste, de su cadena de producción, siendo esta una de sus principales ventajas competitivas.",
                "La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.",
                "Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.",
                "Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.",
                "La empresa dispone y ejecuta un sistemático plan de marketing y ventas.",
                "La empresa tiene optimizada su gestión financiera.",
                "La empresa busca continuamente mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo.",
                "La empresa es referente en su sector en el lanzamiento de innovadores productos y servicios de éxito demostrado en el mercado.",
                "Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.",
                "Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.",
                "La empresa siempre trabaja conforme a una estrategia y objetivos claros.",
                "La gestión del circulante está optimizada.",
                "Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.",
                "Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.",
                "La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día.",
                "Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.",
                "El servicio al cliente que prestamos es una de nuestras principales ventajas competitivas respecto a nuestros competidores."
            ];


            $opciones = [
                "0 = En total desacuerdo",
                "1 = No está de acuerdo",
                "2 = Está de acuerdo",
                "3 = Bastante de acuerdo",
                "4 = En total acuerdo"
            ];

            foreach ($preguntas as $i => $pregunta) {
                echo "<tr><td>" . ($i + 1) . "</td>";
                echo "<td>" . htmlspecialchars($pregunta) . "</td><td>";

                $valorSeleccionado = $valores[$i] ?? -1;
                echo "<select name='valor_$i' onchange='guardarValor(\"valoracion\", $i, this.value)' class='form-select'>";
                
                echo "<option value='' disabled selected>Seleccione una opción</option>"; // Opción por defecto
                foreach ($opciones as $j => $opcion) {
                    $selected = ($valorSeleccionado == $j) ? 'selected' : '';
                    echo "<option value='$j' $selected>$opcion</option>";
                }
                
                echo "</select></td></tr>";
            }
            
            ?>
        </table>
    </form>

    <!-- Mostrar el porcentaje de mejora calculado -->
    <div id="potencial" class="potencial-container">
    Potencial de Mejora de la Cadena de Valor Interna: <span class="porcentaje"><?php echo $porcentaje_mejora; ?>%</span>
</div>


<h2 class="section-title">Fortalezas</h2>
<table class="styled-table">
    <?php for ($i = 0; $i < 4; $i++): ?>
        <tr>
            <td class="label">F<?php echo $i + 1; ?>:</td>
            <td>
                <input type="text" name="fortaleza_<?php echo $i; ?>" 
                       value="<?php echo htmlspecialchars($fortalezas[$i]); ?>" 
                       oninput="guardarValor('fortaleza', <?php echo $i; ?>, this.value)" 
                       class="input-field">
            </td>
        </tr>
    <?php endfor; ?>
</table>

<h2 class="section-title">Debilidades</h2>
<table class="styled-table">
    <?php for ($i = 0; $i < 4; $i++): ?>
        <tr>
            <td class="label">D<?php echo $i + 1; ?>:</td>
            <td>
                <input type="text" name="debilidad_<?php echo $i; ?>" 
                       value="<?php echo htmlspecialchars($debilidades[$i]); ?>" 
                       oninput="guardarValor('debilidad', <?php echo $i; ?>, this.value)" 
                       class="input-field">
            </td>
        </tr>
    <?php endfor; ?>
</table>

    </div>
</body>
</html>
