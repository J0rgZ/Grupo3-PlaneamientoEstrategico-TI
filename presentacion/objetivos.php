<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

$plan_id = $_GET['plan_id'];

// Obtener el plan desde MongoDB
$plan = $db->planes->findOne(['_id' => new MongoDB\BSON\ObjectId($plan_id)]);

if ($plan) {
    $mision = $plan['mision'] ?? '';
    $objetivos_generales = $plan['objetivos_generales'] ?? ['', '', ''];

    // Convertir objetivos_especificos a un array PHP si es BSONArray
    $objetivos_especificos = isset($plan['objetivos_especificos']) ? json_decode(json_encode($plan['objetivos_especificos']), true) : [['', ''], ['', ''], ['', '']];
    
    // Asegurarse de que "objetivos_especificos" tiene tres subarreglos con dos elementos cada uno
    for ($i = 0; $i < 3; $i++) {
        if (!isset($objetivos_especificos[$i])) {
            $objetivos_especificos[$i] = ['', ''];
        } else {
            // Asegurar que cada subarreglo tiene dos elementos
            $objetivos_especificos[$i] = array_pad($objetivos_especificos[$i], 2, '');
        }
    }
} else {
    header("Location: index.php");
    exit();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['csrf_token'] == $_SESSION['csrf_token']) {
    if (empty($mision)) {
        $_SESSION['error_message'] = "Primero debe completar su misión en el paso 1.";
        header("Location: objetivos.php?plan_id=$plan_id");
        exit();
    }

    $mision = $_POST['mision'];
    $objetivos_generales = [
        $_POST['objetivo_general_1'],
        $_POST['objetivo_general_2'],
        $_POST['objetivo_general_3']
    ];
    $objetivos_especificos = [
        [
            $_POST['objetivo_especifico_1_1'],
            $_POST['objetivo_especifico_1_2']
        ],
        [
            $_POST['objetivo_especifico_2_1'],
            $_POST['objetivo_especifico_2_2']
        ],
        [
            $_POST['objetivo_especifico_3_1'],
            $_POST['objetivo_especifico_3_2']
        ]
    ];

    $result = $db->planes->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
        ['$set' => [
            'mision' => $mision,
            'objetivos_generales' => $objetivos_generales,
            'objetivos_especificos' => $objetivos_especificos
        ]]
    );

    if ($result->getModifiedCount() > 0) {
        $_SESSION['success_message'] = "Objetivos actualizados exitosamente.";
    } else {
        $_SESSION['error_message'] = "No se realizaron cambios en los objetivos.";
    }

    header("Location: objetivos.php?plan_id=$plan_id");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4. OBJETIVOS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* Contenedor Principal */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            width: 100%;
        }

        /* Encabezados */
        h1 {
            color: #1a1a1a;
            font-size: 2.2em;
            margin-bottom: 1.5rem;
            border-bottom: 3px solid #0099cc;
            padding-bottom: 0.5rem;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.6em;
            margin: 2rem 0 1rem;
        }

        /* Tabla General */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 20px;
            text-align: left;
            vertical-align: middle;
        }

        /* Encabezados de tablas */
        th {
            background: linear-gradient(135deg, #0099cc, #007ba7);
            color: white;
            font-weight: 600;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 3px solid #007ba7;
        }

        /* Filas de la tabla */
        td {
            color: #2d3748;
            font-size: 1em;
            line-height: 1.6;
            border-bottom: 1px solid #f0f1f3;
        }

        /* Alternancia de filas (estilo de bandas) */
        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Fila activa (hover) */
        tr:hover {
            background-color: #f1f7fc;
            cursor: pointer;
        }

        /* Estilo para celdas específicas */
        td:first-child {
            font-weight: bold;
        }

        td.text-center {
            text-align: center;
        }

        td.text-right {
            text-align: right;
        }

        /* Estilo para tablas específicas */
        .meta-table th, .meta-table td {
            text-align: center;
            padding: 12px 15px;
        }

        .objetivos-table th {
            text-align: center;
            background-color: #0099cc;
            color: white;
            padding: 12px 15px;
        }

        .objetivos-table td {
            padding: 15px;
            vertical-align: top;
        }

        .objetivos-table textarea {
            width: 100%;
            min-height: 100px;
            margin: 5px 0;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            resize: vertical;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }

        .objetivos-table textarea:focus {
            border-color: #0099cc;
            box-shadow: 0 0 0 3px rgba(0, 153, 204, 0.2);
            outline: none;
        }

        /* Barra de progreso en pasos */
        .progress-container {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
            align-items: center;
        }

        .progress-step {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .step {
            width: 30px;
            height: 30px;
            background-color: #d1d1d1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .step.completed {
            background-color: #0099cc;
        }

        .step-line {
            flex: 1;
            height: 4px;
            background-color: #d1d1d1;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .step-line.active {
            background-color: #0099cc;
        }
        /* Botones de navegación */
        .nav-button {
            background-color: #0099cc;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
            text-align: center;
            margin: 0 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-button:hover {
            background-color: #007ba7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Estilos para listas */
        ul, ol {
            padding-left: 20px;
            margin: 15px 0;
        }

        li {
            margin: 8px 0;
            line-height: 1.6;
            color: #2d3748;
        }

        /* Contenido informativo */
        .objetivos-info, .uen-info {
            background-color: #f8fafc;
            border-left: 4px solid #0099cc;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        /* Imagen de la pirámide */
        .piramide {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Mensajes de éxito y error */
        .success-message, .error-message {
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .success-message {
            background-color: #def7ec;
            color: #03543f;
            border: 1px solid #84e1bc;
        }

        .error-message {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px 10px;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            th, td {
                min-width: 120px;
            }

            .progress-container {
                flex-direction: column;
                align-items: center;
            }

            .step-line {
                display: none;
            }
        }

        /* Estilo para los botones de navegación al final del formulario */
        .form-navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .form-navigation-buttons .nav-button {
            padding: 10px 20px;
            background-color: #007ba7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .form-navigation-buttons .nav-button:hover {
            background-color: #0099cc;
        }

    </style>
</head>
<body>
    <!-- Barra de progreso en pasos -->
    <div class="progress-container">
        <div class="progress-step">
            <div class="step completed">1</div>
            <div class="step-line active"></div>
            <div class="step completed">2</div>
            <div class="step-line active"></div>
            <div class="step completed">3</div>
            <div class="step-line active"></div>
            <div class="step completed">4</div>
            <div class="step-line active"></div>
            <div class="step">5</div>
            <div class="step-line"></div>
            <div class="step">6</div>
            <div class="step-line"></div>
            <div class="step">7</div>
            <div class="step-line"></div>
            <div class="step">8</div>
        </div>
    </div>

    <div class="container">
        <main>
            <h1>4. OBJETIVOS ESTRATÉGICOS</h1>
            <div class="content">
                <div class="objetivos-info">
                    <p>El siguiente paso es establecer los objetivos de una empresa en relación al sector al que pertenece.</p>
                    <p>Un OBJETIVO ESTRATÉGICO es un fin deseado, clave para la organización y para la consecución de su visión. Para una correcta planificación, construya los objetivos formando una pirámide. Los objetivos de cada nivel indican qué es lo que quiere lograrse, siendo la estructura de objetivos como está en el nivel inmediatamente inferior a esa única al cómo. Por tanto, cada objetivo es un fin en sí mismo, pero también es a su vez un medio para el logro del objetivo del nivel superior.</p>
                </div>

                <img src="../img/piramide_objetivos.jpeg" alt="Pirámide de Objetivos" class="piramide">

                <table>
                    <tr>
                        <th>Tipo de Objetivo</th>
                        <th>Descripción</th>
                    </tr>
                    <tr>
                        <td>Objetivos estratégicos</td>
                        <td>Concretan el contenido de la misión. Suelen referirse al crecimiento, rentabilidad y la sostenibilidad de la empresa. Su horizonte es entre 3 a 5 años.</td>
                    </tr>
                    <tr>
                        <td>Objetivos operativos</td>
                        <td>Son la concreción anual de los objetivos estratégicos. Han de ser claros, concisos y medibles. Se pueden distinguir dos tipos de objetivos específicos:</td>
                    </tr>
                </table>

                <ol>
                    <li>Funcionales: objetivos formulados por áreas o departamentos</li>
                    <li>Individuales: objetivos que se centran en actividades y acciones concretas</li>
                </ol>

                <p>Cualquier objetivo formulado tiene que presentar los siguientes atributos:</p>

                <table class="meta-table">
                    <tr>
                        <th>M</th>
                        <td>MEDIBLES: que se les pueda asignar indicadores cuantitativos</td>
                    </tr>
                    <tr>
                        <th>E</th>
                        <td>ESPECÍFICOS: que se centren en una meta concreta, de forma clara, breve y comprensible</td>
                    </tr>
                    <tr>
                        <th>T</th>
                        <td>TRAZABLES: que permita un registro de seguimiento y control</td>
                    </tr>
                    <tr>
                        <th>A</th>
                        <td>ALCANZABLES: realistas y motivadores</td>
                    </tr>
                    <tr>
                        <th>S</th>
                        <td>RETADORES: desafiantes pero consecuentes con los recursos disponibles</td>
                    </tr>
                </table>

                <h2>Ejemplos de Objetivos</h2>
                <ul>
                    <li>Alcanzar los niveles de ventas previstos para los nuevos productos</li>
                    <li>Reducir la rotación del personal del almacén</li>
                    <li>Reducir el número de entregas fuera de plazo</li>
                    <li>Reducir la siniestralidad en nivel fijado</li>
                    <li>Alcanzar los niveles de producción previstos</li>
                    <li>Mejorar la calidad de entrega de los productos en el plazo previsto</li>
                </ul>

                <h2>Unidades Estratégicas de Negocio (UEN)</h2>
                <div class="uen-info">
                    <p>En empresas de gran tamaño, se pueden formular los objetivos estratégicos en función de sus diferentes unidades estratégicas de negocio (UEN). Estas UEN se hacen especialmente necesarias en las empresas diversificadas o con multiactividad donde la heterogeneidad de sus distintos negocios hace necesario un tratamiento estratégico conjunto de los mismos.</p>
                    <p>Se entiende por unidad estratégica de negocio (UEN) ("strategic business unit" (SBU)) un conjunto homogéneo de actividades o negocios, desde el punto de vista estratégico, es decir, para el cual es posible formular una estrategia común y a su vez diferente de la estrategia adecuada para otras actividades y/o unidades estratégicas. La estrategia de cada unidad es así autónoma, pero no independiente de las demás unidades estratégicas, puesto que se integran en la estrategia de la empresa.</p>
                    <p>¿Cómo podemos identificar las UEN?</p>
                    <p>La identificación de las UEN es clave y se puede realizar a partir de las tres siguientes dimensiones:</p>
                    <ul>
                        <li>Funciones: Necesidades cubiertas por el producto o servicio.</li>
                        <li>Grupos de clientes: Necesidades cubiertas por el producto o servicio.</li>
                        <li>Tecnología: Forma en la cual la empresa cubre a través del producto o servicio la necesidad de la clientela.</li>
                    </ul>
                </div>

                <textarea placeholder="En su caso, comente en este apartado las distintas UEN que tiene su empresa"></textarea>

                <h2>Definición de Objetivos</h2>
                <p>A continuación reflexione sobre la misión, visión y valores definidos y establezca los objetivos estratégicos y específicos de su empresa. Le proponemos que comience con definir 3 objetivos estratégicos y dos específicos para cada uno de ellos.</p>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="objetivos.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <table class="objetivos-table">
                <tr>
                    <th>MISIÓN</th>
                    <th>OBJETIVOS GENERALES O ESTRATÉGICOS</th>
                    <th>OBJETIVOS ESPECÍFICOS</th>
                </tr>
                <tr>
                    <td rowspan="3">
                        <textarea name="mision" readonly><?php echo htmlspecialchars($mision); ?></textarea>
                        <?php if (empty($mision)): ?>
                            <div class="error-message">Primero debe completar su misión en el paso 1.</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <textarea name="objetivo_general_1" required placeholder="Aquí va el objetivo general"><?php echo htmlspecialchars($objetivos_generales[0]); ?></textarea>
                    </td>
                    <td>
                        <textarea name="objetivo_especifico_1_1" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[0][0]); ?></textarea>
                        <textarea name="objetivo_especifico_1_2" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[0][1]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <textarea name="objetivo_general_2" required placeholder="Aquí va el objetivo general"><?php echo htmlspecialchars($objetivos_generales[1]); ?></textarea>
                    </td>
                    <td>
                        <textarea name="objetivo_especifico_2_1" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[1][0]); ?></textarea>
                        <textarea name="objetivo_especifico_2_2" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[1][1]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <textarea name="objetivo_general_3" required placeholder="Aquí va el objetivo general"><?php echo htmlspecialchars($objetivos_generales[2]); ?></textarea>
                    </td>
                    <td>
                        <textarea name="objetivo_especifico_3_1" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[2][0]); ?></textarea>
                        <textarea name="objetivo_especifico_3_2" required placeholder="Aquí debe completar los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[2][1]); ?></textarea>
                    </td>
                </tr>
            </table>

            <!-- Botones de navegación al final -->
            <div class="form-navigation-buttons">
                <button type="submit" class="nav-button">Guardar los Cambios</button>
                <div>
                    <a href="mision.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" class="nav-button">Anterior</a>
                    <a href="vision.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>" class="nav-button">Siguiente</a>
                </div>
            </div>
        </form>
    </div>
</body>
