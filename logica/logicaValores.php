<?php
// guardar.php

// Incluir el archivo de conexión a MongoDB
require '../datos/conexion.php'; // Asegúrate de que la ruta sea correcta

// Verificar si se ha recibido una solicitud POST con los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valores']) && isset($_POST['action'])) {
    // Capturar y limpiar los datos del formulario
    $valores = trim($_POST['valores']);
    $accion = $_POST['action'];

    // Verificar que el campo 'valores' no esté vacío
    if ($valores !== "") {
        try {
            // Seleccionar la colección de MongoDB (ajusta el nombre si es necesario)
            $collection = $db->valores;

            // Verificar si el valor ya existe en la colección para evitar duplicados
            $existingValue = $collection->findOne(['valores' => $valores]);

            if ($existingValue) {
                // Mostrar un mensaje de error y redirigir de vuelta al formulario
                echo "<script>
                        alert('El valor ya existe en la base de datos.');
                        window.history.back();
                      </script>";
                exit();
            } else {
                // Insertar los valores en la colección con una marca de tiempo
                $insertResult = $collection->insertOne([
                    'valores' => $valores,
                    'fecha' => new MongoDB\BSON\UTCDateTime()
                ]);

                // Opcional: Puedes manejar la respuesta según necesites
                // Por ejemplo, podrías almacenar el ID insertado si lo necesitas
            }
        } catch (Exception $e) {
            // Manejar errores de conexión o inserción
            echo "<script>
                    alert('Ocurrió un error al guardar los valores: " . addslashes($e->getMessage()) . "');
                    window.history.back();
                  </script>";
            exit();
        }
    } else {
        // Mostrar un mensaje si el campo 'valores' está vacío y redirigir de vuelta al formulario
        echo "<script>
                alert('Por favor, ingrese los valores antes de navegar.');
                window.history.back();
              </script>";
        exit();
    }

    // Redirigir según la acción seleccionada
    switch ($accion) {
        case 'index':
            header("Location: ../presentacion/index.php");
            exit();
        case 'vision':
            header("Location: ../presentacion/vision.php");
            exit();
        case 'resumen':
            header("Location: ../presentacion/resumen.php");
            exit();
        default:
            // Redirigir a una página por defecto si la acción no coincide
            header("Location: ../presentacion/index.php");
            exit();
    }
} else {
    // Si no se recibe una solicitud POST válida, redirigir al formulario
    header("Location: valores.php");
    exit();
}
?>
