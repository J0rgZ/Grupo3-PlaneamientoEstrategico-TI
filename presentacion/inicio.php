<?php
session_start();
require '../datos/conexion.php'; // Ajusta la ruta si es necesario

if (!isset($_SESSION['user_id'])) {
    die("No estás autenticado.");
}

$user_id = $_SESSION['user_id'];
$collection = $db->planes; // Asegúrate de que este es el nombre correcto de tu colección

// Obtener el nombre del usuario
$user = $db->usuarios->findOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);

// Obtener los planes estratégicos del usuario
$planes = $collection->find(['user_id' => $user_id])->toArray();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Agregar un nuevo plan
if (isset($_POST['agregar'])) {
    $nuevo_plan = $_POST['nuevo_plan'] ?? '';
    if ($nuevo_plan) {
        $collection->insertOne([
            'user_id' => $user_id, 
            'nombre' => $nuevo_plan, 
            'fecha_creacion' => new MongoDB\BSON\UTCDateTime(), 
            'fecha_modificacion' => new MongoDB\BSON\UTCDateTime(), 
            'estado' => 'No completado'
        ]);
        header("Location: inicio.php");
        exit();
    }
}

// Eliminar un plan
if (isset($_POST['eliminar'])) {
    $id_plan = $_POST['id_plan'];
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id_plan)]);
    header("Location: inicio.php");
    exit();
}

// Editar un plan
if (isset($_POST['editar'])) {
    $id_plan = $_POST['id_plan'];
    $nombre_plan = $_POST['nombre_plan'];
    if ($nombre_plan) {
        $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id_plan)],
            ['$set' => [
                'nombre' => $nombre_plan, 
                'fecha_modificacion' => new MongoDB\BSON\UTCDateTime()
            ]]
        );
        header("Location: inicio.php");
        exit();
    }
}

// Iniciar un plan
if (isset($_POST['iniciar'])) {
    $id_plan = $_POST['id_plan'];
    header("Location: index.php?plan_id=" . $id_plan); // Cambia esto según la lógica de tu aplicación
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Planes Estratégicos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-primary, .btn-danger {
            margin: 0 5px;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .form-group input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($user->username); ?></h1>
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Cerrar sesión</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                Agregar Nuevo Plan Estratégico
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="nuevo_plan" class="form-control" placeholder="Nombre del nuevo plan" required>
                    </div>
                    <button type="submit" name="agregar" class="btn btn-primary">Agregar Plan</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Mis Planes Estratégicos
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="thead-dark">
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
                                <td><?php echo htmlspecialchars($plan->estado); ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarModal<?php echo $plan->_id; ?>">Editar</button>

                                    <div class="modal fade" id="editarModal<?php echo $plan->_id; ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editarModalLabel">Editar Plan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                                        <div class="form-group">
                                                            <input type="text" name="nombre_plan" class="form-control" value="<?php echo htmlspecialchars($plan->nombre); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                        <button type="submit" name="eliminar" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este plan?');">Eliminar</button>
                                    </form>

                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="id_plan" value="<?php echo $plan->_id; ?>">
                                        <button type="submit" name="iniciar" class="btn btn-success">Iniciar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

