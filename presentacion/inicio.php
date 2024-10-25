<?php
session_start();
require '../datos/conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

$user_id = $_SESSION['user_id'];
$collection = $db->planes;
$user = $db->usuarios->findOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);
$planes = $collection->find(['user_id' => $user_id])->toArray();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['agregar'])) {
    $nuevo_plan = $_POST['nuevo_plan'] ?? '';
    if ($nuevo_plan) {
        $collection->insertOne([
            'user_id' => $user_id, 
            'nombre' => $nuevo_plan, 
            'fecha_creacion' => new MongoDB\BSON\UTCDateTime(), 
            'fecha_modificacion' => new MongoDB\BSON\UTCDateTime(), 
            'estado' => 'No iniciado',
            'mision' => '',
            'vision' => '',
            'valores' => '',
            'objetivos_generales' => [],
            'preguntas' => [],
            'fortalezas' => [],
            'debilidades' => []
        ]);
        header("Location: inicio.php");
        exit();
    }
}



if (isset($_POST['eliminar'])) {
    $id_plan = $_POST['id_plan'];
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id_plan)]);
    header("Location: inicio.php");
    exit();
}

if (isset($_POST['editar'])) {
    $id_plan = $_POST['id_plan'];
    $nombre_plan = $_POST['nombre_plan'];
    $estado_plan = $_POST['estado_plan']; // Captura el nuevo estado
    if ($nombre_plan) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id_plan)],
            ['$set' => [
                'nombre' => $nombre_plan, 
                'estado' => $estado_plan, // Actualiza el estado
                'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
            ]]
        );
        header("Location: inicio.php");
        exit();
    }
}

if (isset($_POST['iniciar'])) {
    $id_plan = $_POST['id_plan'];
    header("Location: index.php?plan_id=" . $id_plan);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Planes Estratégicos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #121212; /* Fondo oscuro */
            color: #e0e0e0; /* Texto claro */
            padding: 20px;
            font-family: 'Arial', sans-serif;
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
            color: #00bcd4; /* Color turquesa */
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }
        .card {
            background-color: #1e1e1e; /* Fondo de tarjeta */
            border: none;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .card-header {
            background-color: #292b2c; /* Fondo más claro */
            color: #ffffff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        .table {
            background-color: #1e1e1e; /* Fondo de la tabla */
            border-radius: 10px;
            overflow: hidden; /* Bordes redondeados */
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            border: none; /* Sin bordes */
            color: #ffffff; /* Color de texto en celdas */
        }
        .table th {
            background-color: #00bcd4; /* Color turquesa */
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10; /* Para que los encabezados queden por encima */
        }
        .table tbody tr {
            transition: background-color 0.3s;
        }
        .table tbody tr:hover {
            background-color: #292b2c; /* Fondo oscuro al pasar el mouse */
        }
        .status-bar {
            height: 20px;
            border-radius: 5px;
        }
        .status-completado {
            background-color: #28a745; /* Verde */
        }
        .status-no-completado {
            background-color: #ffc107; /* Amarillo */
        }
        .status-en-proceso {
            background-color: #17a2b8; /* Turquesa */
        }
        .btn-primary {
            background-color: #00bcd4; /* Color turquesa */
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0288d1; /* Turquesa oscuro */
        }

        /* Estilos para el tema claro */
        .light-theme {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000; /* Texto negro */
        }
        .light-theme .card {
            background-color: #f8f9fa; /* Fondo claro de la tarjeta */
            color: #000; /* Texto negro en tarjeta */
        }
        .light-theme .table {
            background-color: #ffffff; /* Fondo blanco para la tabla */
        }
        .light-theme .table th {
            background-color: #007bff; /* Color azul para el encabezado de la tabla */
            color: #fff;
        }
        .light-theme .table td {
            color: #000; /* Color negro para el texto de las celdas */
        }
        .light-theme .btn-primary {
            background-color: #007bff; /* Azul */
        }
        .light-theme .btn-primary:hover {
            background-color: #0056b3; /* Azul oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tasks"></i> Bienvenido, <?php echo htmlspecialchars($user->username); ?></h1>
            <div>
                <button id="themeToggle" class="btn btn-secondary"><i class="fas fa-adjust"></i> Cambiar Tema</button>
                <form method="post" style="display:inline;">
                    <button type="submit" name="logout" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle"></i> Agregar Nuevo Plan Estratégico
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <input type="text" name="nuevo_plan" class="form-control" placeholder="Nombre del nuevo plan" required>
                    </div>
                    <button type="submit" name="agregar" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Plan</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Mis Planes Estratégicos
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Plan</th>
                            <th>Fecha de Creación</th>
                            <th>Fecha de Modificación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planes as $index => $plan): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($plan->nombre); ?></td>
                                <td><?php echo $plan->fecha_creacion ? $plan->fecha_creacion->toDateTime()->format('Y-m-d H:i:s') : 'No disponible'; ?></td>
                                <td><?php echo $plan->fecha_modificacion ? $plan->fecha_modificacion->toDateTime()->format('Y-m-d H:i:s') : 'No disponible'; ?></td>
                                <td>
                                    <div class="status-bar 
                                        <?php echo ($plan->estado === 'Completado') ? 'status-completado' : (($plan->estado === 'No iniciado') ? 'status-no-completado' : 'status-en-proceso'); ?>">
                                    </div>
                                    <span><?php echo htmlspecialchars($plan->estado); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                                        <div class="text-center me-3">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $plan->_id; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <span class="d-none d-sm-block">Editar</span>
                                        </div>

                                        <div class="text-center me-3">
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                                <button type="submit" name="eliminar" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este plan?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <span class="d-none d-sm-block">Eliminar</span>
                                            </form>
                                        </div>

                                        <div class="text-center">
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                                <button type="submit" name="iniciar" class="btn btn-success btn-sm">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <span class="d-none d-sm-block">Iniciar</span>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="editarModal<?php echo $plan->_id; ?>" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editarModalLabel">Editar Plan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                                        <div class="mb-3">
                                                            <input type="text" name="nombre_plan" class="form-control" value="<?php echo htmlspecialchars($plan->nombre); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="estado_plan" class="form-label">Estado</label>
                                                            <select name="estado_plan" class="form-select" required>
                                                                <option value="No iniciado" <?php echo ($plan->estado === 'No iniciado') ? 'selected' : ''; ?>>No iniciado</option>
                                                                <option value="En proceso" <?php echo ($plan->estado === 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                                                                <option value="Completado" <?php echo ($plan->estado === 'Completado') ? 'selected' : ''; ?>>Completado</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Cargar el tema guardado en LocalStorage
        if (localStorage.getItem('theme') === 'light') {
            body.classList.add('light-theme');
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('light-theme');

            // Guardar la preferencia en LocalStorage
            if (body.classList.contains('light-theme')) {
                localStorage.setItem('theme', 'light');
                themeToggle.textContent = 'Cambiar a Tema Oscuro';
            } else {
                localStorage.setItem('theme', 'dark');
                themeToggle.textContent = 'Cambiar a Tema Claro';
            }
        });
    </script>
</body>
</html>
