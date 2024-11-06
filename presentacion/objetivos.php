<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Generar un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Recuperar datos de la base de datos
$collection = $db->objetivos;
$user_id = $_SESSION['user_id'];
$documento = $collection->findOne(['user_id' => $user_id]);

$mision = $documento['mision'] ?? '';
$objetivos_generales = $documento['objetivos_generales'] ?? ['', '', ''];
$objetivos_especificos = $documento['objetivos_especificos'] ?? [2 => ['', ''], 3 => ['', '']];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetivos Estratégicos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-direction: column;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* Barra de progreso en pasos */
        .progress-container {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
        }

        .progress-step {
            width: 100%;
            display: flex;
            justify-content: space-between;
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

        /* Contenedor principal */
        .container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.4s ease;
            margin-bottom: 80px; /* Para que los botones no se superpongan */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        header, footer {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .indice {
            background-color: #ff7f7f;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            text-decoration: none;
        }

        h1 {
            background-color: #0099cc;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 2em;
        }

        .content {
            margin-top: 20px;
        }

        .objetivos-info, .uen-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .piramide {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0099cc;
            color: white;
        }

        .meta-table, .objetivos-table {
            margin-top: 20px;
            width: 100%;
        }

        .meta-table th, .objetivos-table th {
            background-color: #808080;
            color: white;
        }

        .meta-table td {
            background-color: #f8f8f8;
        }

        .objetivos-table td {
            height: 50px;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 1.1em;
            border-radius: 8px;
            border: 2px solid #ddd;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            resize: none;
            transition: all 0.3s ease;
        }

        textarea:focus {
            border-color: #0099cc;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 153, 204, 0.2);
            outline: none;
        }

        .nav-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0099cc;
            color: white;
            text-decoration: none;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .nav-button:hover {
            background-color: #007ba7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h1 {
                font-size: 1.5em;
            }

            .progress-container {
                margin: 10px;
            }

            table {
                font-size: 0.9em;
            }

            .meta-table, .objetivos-table {
                font-size: 0.9em;
            }

            textarea {
                font-size: 1em;
                height: 80px;
            }

            footer {
                position: fixed;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                width: 100%;
                display: flex;
                justify-content: center;
                padding: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                background-color: #fff;
                z-index: 10;
            }

            footer .nav-button {
                margin: 0 5px;
            }
        }

        @media (max-width: 480px) {
            .nav-button {
                font-size: 0.9em;
                padding: 8px 15px;
            }
        }

        


            /* Estilo para los botones de navegación */
    .navigation-buttons {
        position: fixed;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 10px;
        z-index: 9999; /* Asegurarse de que esté por encima de otros contenidos */
        padding: 10px;
        background-color: #fff; /* Fondo blanco para que no se mezcle con el contenido */
        box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil para que se distinga */
    }

    .nav-button {
        background-color: #0099cc;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        font-size: 1.1em;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .nav-button i {
        font-size: 1.2em; /* Icono un poco más grande */
    }

    .nav-button:hover {
        background-color: #007ba7;
        transform: translateY(-2px); /* Efecto sutil al pasar el ratón */
    }

    .nav-button:active {
        background-color: #005f7a;
        transform: translateY(0); /* Efecto al hacer clic */
    }

    /* Para pantallas más pequeñas */
    @media (max-width: 768px) {
        .nav-button {
            font-size: 1em;
            padding: 10px 15px;
        }

        .navigation-buttons {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            gap: 5px;
        }
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
            <div class="step">3</div>
            <div class="step-line"></div>
            <div class="step">4</div>
            <div class="step-line"></div>
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

                <form action="../logica/logicaObjetivos.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <table class="objetivos-table">
                        <tr>
                            <th>MISIÓN</th>
                            <th>OBJETIVOS GENERALES O ESTRATÉGICOS</th>
                            <th>OBJETIVOS ESPECÍFICOS</th>
                        </tr>
                        <tr>
                            <td rowspan="3"><textarea name="mision" required><?php echo htmlspecialchars($mision); ?></textarea></td>
                            <td><textarea name="objetivo_general_1" required><?php echo htmlspecialchars($objetivos_generales[0]); ?></textarea></td>
                            <td>
                                <textarea name="objetivo_especifico_1_1" required><?php echo htmlspecialchars($objetivos_especificos[2][0]); ?></textarea>
                                <textarea name="objetivo_especifico_1_2" required><?php echo htmlspecialchars($objetivos_especificos[2][1]); ?></textarea>
                            </td>
                        </tr>
                        <?php
                        for ($i = 1; $i < 3; $i++) {
                            echo "<tr>
                                    <td><textarea name='objetivo_general_" . ($i + 1) . "' required>" . htmlspecialchars($objetivos_generales[$i]) . "</textarea></td>
                                    <td>
                                        <textarea name='objetivo_especifico_" . ($i + 1) . "_1' required>" . htmlspecialchars($objetivos_especificos[$i + 2][0]) . "</textarea>
                                        <textarea name='objetivo_especifico_" . ($i + 1) . "_2' required>" . htmlspecialchars($objetivos_especificos[$i + 2][1]) . "</textarea>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </table>
                    <button type="submit" class="nav-button">Guardar los Cambios</button>
                </form>
            </div>
        </main>
    </div>

    <!-- Botones de navegación en la parte inferior -->
    <div class="navigation-buttons">
        <button class="nav-button" onclick="window.location.href='index.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-home"></i> INDICE
        </button>
        <button class="nav-button" onclick="window.location.href='valores.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-hand-holding-heart icon"></i> 3. VALORES
        </button>
        <button class="nav-button" onclick="window.location.href='analisis.php?plan_id=<?php echo htmlspecialchars($plan_id); ?>'">
            <i class="fas fa-chart-line icon"></i> 5. ANALISIS INTERNO Y EXTERNO
        </button>
    </div>

</body>
</html>
