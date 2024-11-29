<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php';

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inicializar las variables
$mision_usuario = "";
$vision_usuario = "";
$valores_usuario = "";
$nombre="";
$plan_id = $_GET['plan_id'] ?? '';
$fecha_creacion = $_GET['plan_id'] ?? '';


$user_id = $_SESSION['user_id'];
$collection = $db->diagnosticos;

// Obtener los datos guardados del usuario
$user_diagnostico = $collection->findOne(['user_id' => $user_id]);

// Valores de valoraciones, fortalezas y debilidades
//'nombre' => $nuevo_plan,
$amenazas = $user_diagnostico['amenazas'] ?? array_fill(0, 25, 0); // 25 preguntas
$fortalezas = (array)($user_diagnostico['fortalezas'] ?? array_fill(0, 4, ''));
$debilidades = (array)($user_diagnostico['debilidad'] ?? array_fill(0, 4, ''));
//$oportunidades = (array)$user_diagnostico['oportunidades'] ?? array_fill(0, 25, 0);

// Recuperar los datos del plan desde MongoDB
try {
    $collection = $db->planes;

    // Buscar el plan con el plan_id y user_id correspondiente
    $documento = $collection->findOne([
        '_id' => new MongoDB\BSON\ObjectId($plan_id),
        'user_id' => $_SESSION['user_id']
    ]);

    // Verificar si se encontró el documento y si contiene los campos
    if ($documento) {
        $mision_usuario = $documento['mision'] ?? '';
        $vision_usuario = $documento['vision'] ?? '';
        $valores_usuario = $documento['valores'] ?? '';
        $nombre = $documento['nombre'] ?? '';
        $fecha_creacion = $documento['fecha'] ?? '';
        // Recuperar los objetivos desde MongoDB
        $objetivos_generales = $documento['objetivos_generales'] ?? [];
        $objetivos_especificos = $documento['objetivos_especificos'] ?? [];
        

    } else {
        // Si no se encontró el documento, inicializamos las variables vacías
        $mision_usuario = $vision_usuario = $valores_usuario = "";

    }
} catch (Exception $e) {
    error_log("Error al recuperar datos: " . $e->getMessage());
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_mision = $_POST['mision'] ?? '';
    $nueva_vision = $_POST['vision'] ?? '';
    $nuevos_valores = $_POST['valores'] ?? '';

    if ($nueva_mision || $nueva_vision || $nuevos_valores) {
        try {
            $result = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($plan_id)],
                ['$set' => [
                    'mision' => $nueva_mision,
                    'vision' => $nueva_vision,
                    'valores' => $nuevos_valores,
                    'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
                ]]
            );

            if ($result->getModifiedCount() > 0) {
                $_SESSION['success_message'] = "Datos actualizados exitosamente.";
            } else {
                $_SESSION['error_message'] = "No se realizaron cambios.";
            }
        } catch (Exception $e) {
            error_log("Error al actualizar datos: " . $e->getMessage());
            $_SESSION['error_message'] = "Ocurrió un error al actualizar los datos. Por favor, intenta nuevamente.";
        }

        header("Location: mision.php?plan_id=$plan_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Resumen Ejecutivo del Plan Estratégico</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            width: 150px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #3f51b5;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-weight: bold;
            font-size: 1.3em;
            margin-bottom: 10px;
            color: #3f51b5;
            text-align: center;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            color: #333;
            background-color: #fafafa;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .input-group input, .input-group textarea {
            font-size: 15px;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .three-column {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .actions-list {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .actions-list input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #fafafa;
        }

        .button {
            padding: 12px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #3f51b5;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #303f9f;
        }

        .section-title {
            border-bottom: 2px solid #3f51b5;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .container h1 {
            font-family: 'Arial', sans-serif;
            color: #3f51b5;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Resumen Ejecutivo del Plan Estratégico</h1>

        <!-- Información General -->
        <div class="section">
            <div class="input-group">
                <label for="empresa">Nombre de la empresa / proyecto:</label>
                <textarea id="empresa" rows="4" placeholder="Introduzca el nombre de la empresa / proyecto" name="nombre" required><?php echo htmlspecialchars($nombre); ?></textarea>

            </div>
            <div class="input-group">
                <label for="fecha">Fecha de elaboración:</label>
                <textarea type="date" id="fecha" rows="4" placeholder="Fecha de creacion " name="nombre" required><?php echo htmlspecialchars($fecha_creacion); ?></textarea>

            </div>
            <div class="input-group">
                <label for="emprendedores">Emprendedores / promotores:</label>
                <input type="text" id="emprendedores" placeholder="Introduzca el/los nombre/s de el/los promotor/es">
            </div>
        </div>

        <!-- Misión, Visión, Valores -->
        <div class="section">
            <div class="section-title">MISIÓN, VISIÓN Y VALORES</div>
            <div>
                <label for="mision">MISIÓN</label>
                <textarea id="mision" rows="4" placeholder="Escriba la misión del proyecto" name="mision" required><?php echo htmlspecialchars($mision_usuario); ?></textarea>
            </div>

            <div>
                <label for="vision">VISIÓN</label>
                <textarea id="vision" rows="4" placeholder="Escriba la visión del proyecto" name="vision"><?php echo htmlspecialchars($vision_usuario ?? ''); ?></textarea>
                </div>

            <div>
                <label for="valores">VALORES</label>
                <textarea id="valores" rows="4" placeholder="Escriba los valores de la empresa" name="valores"><?php echo htmlspecialchars($valores_usuario ?? ''); ?></textarea>
                </div>
        </div>

        <!-- Unidades Estratégicas -->
        <div class="section">
            <div class="section-title">UNIDADES ESTRATÉGICAS</div>
            <textarea id="unidades" rows="4" placeholder="Escriba las unidades estratégicas"></textarea>
        </div>

        <!-- Objetivos Estratégicos -->
        <div class="section">
    <div class="section-title">OBJETIVOS ESTRATÉGICOS</div>
    <div class="three-column">
        <div>
            <label for="mision_obj">MISIÓN</label>
            <textarea id="mision_obj" rows="32" placeholder="Escriba la misión relacionada con los objetivos"required><?php echo htmlspecialchars($mision_usuario); ?></textarea>
        </div>

        <div>
            <label for="generales">Objetivos Generales o Estratégicos</label>
            <textarea id="generales_1" rows="9" placeholder="Escriba los objetivos generales o estratégicos"><?php echo htmlspecialchars($objetivos_generales[0] ?? ''); ?></textarea>
            <textarea id="generales_2" rows="9" placeholder="Escriba los objetivos generales o estratégicos"><?php echo htmlspecialchars($objetivos_generales[1] ?? ''); ?></textarea>
            <textarea id="generales_3" rows="9" placeholder="Escriba los objetivos generales o estratégicos"><?php echo htmlspecialchars($objetivos_generales[2] ?? ''); ?></textarea>
            </div>

        <div>
            <textarea id="especificos_1_1" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[0][0] ?? ''); ?></textarea>
            <textarea id="especificos_1_2" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[0][1] ?? ''); ?></textarea>
            <textarea id="especificos_2_1" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[1][0] ?? ''); ?></textarea>
            <textarea id="especificos_2_2" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[1][1] ?? ''); ?></textarea>
            <textarea id="especificos_3_1" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[2][0] ?? ''); ?></textarea>
            <textarea id="especificos_3_2" rows="1" placeholder="Escriba los objetivos específicos"><?php echo htmlspecialchars($objetivos_especificos[2][1] ?? ''); ?></textarea></div>
        </div>
    </div>


    <form method="POST" action="">
    <!-- Token CSRF (campo oculto) -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <!-- Tabla de análisis FODA -->
    <div class="section">
        <div class="section-title">ANÁLISIS FODA</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Debilidades</th>
                        <th>Amenazas</th>
                        <th>Fortalezas</th>
                        <th>Oportunidades</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <tr>
                            <td>
                                <input type="text" name="debilidad_<?php echo $i; ?>" 
                                       value="<?php echo htmlspecialchars($debilidades[$i]); ?>" 
                                       oninput="guardarValor('debilidad', <?php echo $i; ?>, this.value)" 
                                       class="input-field">
                            </td>
                            <td>
                                <input type="text" name="amenaza_<?php echo $i; ?>" 
                                       value="<?php echo htmlspecialchars($amenazas[$i]); ?>" 
                                       oninput="guardarValor('amenaza', <?php echo $i; ?>, this.value)" 
                                       class="input-field">
                            </td>
                            <td>
                                <input type="text" name="fortaleza_<?php echo $i; ?>" 
                                       value="<?php echo htmlspecialchars($fortalezas[$i]); ?>" 
                                       oninput="guardarValor('fortaleza', <?php echo $i; ?>, this.value)" 
                                       class="input-field">
                            </td>
                            <td>
                                <input type="text" name="oportunidad_<?php echo $i; ?>" 
                                       value="<?php echo htmlspecialchars($oportunidades[$i]); ?>" 
                                       oninput="guardarValor('oportunidad', <?php echo $i; ?>, this.value)" 
                                       class="input-field">
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>




        <!-- Identificación de Estrategia -->
        <div class="section">
            <div class="section-title">IDENTIFICACIÓN DE ESTRATEGIA</div>
            <textarea id="estrategia" rows="4" placeholder="Escriba la estrategia identificada en la Matriz FODA"></textarea>
        </div>

        <!-- Acciones Competitivas -->
        <div class="section">
            <div class="section-title">ACCIONES COMPETITIVAS</div>
            <div class="actions-list">
                <input type="text" placeholder="Acción 1">
                <input type="text" placeholder="Acción 2">
                <input type="text" placeholder="Acción 3">
                <input type="text" placeholder="Acción 4">
                <input type="text" placeholder="Acción 5">
                <input type="text" placeholder="Acción 6">
                <input type="text" placeholder="Acción 7">
                <input type="text" placeholder="Acción 8">
                <input type="text" placeholder="Acción 9">
                <input type="text" placeholder="Acción 10">
                <input type="text" placeholder="Acción 11">
                <input type="text" placeholder="Acción 12">
                <input type="text" placeholder="Acción 13">
                <input type="text" placeholder="Acción 14">
                <input type="text" placeholder="Acción 15">
                <input type="text" placeholder="Acción 16">
            </div>
        </div>

        <!-- Conclusiones -->
        <div class="section">
            <div class="section-title">CONCLUSIONES</div>
            <textarea id="conclusiones" rows="4" placeholder="Anote las conclusiones más relevantes de su Plan"></textarea>
        </div>
        <button onclick="imprimir()">Descargar Pdf</button>

    </div>
<script>
  // Función para activar la interfaz de impresión
  function imprimir() {
    window.print();  // Dispara la interfaz de impresión del navegador
  }
</script>

</body>
</html>
