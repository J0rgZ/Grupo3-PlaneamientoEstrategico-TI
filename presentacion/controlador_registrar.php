<?php
// Incluimos el archivo de conexión
require '../datos/conexion.php';

// Inicializamos variables para mensajes
$error_message = "";
$success_message = "";

// Verificamos que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        try {
            // Comprobar si el usuario ya existe
            $coleccionUsuarios = $db->usuarios; // Asegúrate de que la colección se llama 'usuarios'
            $usuarioExistente = $coleccionUsuarios->findOne(['email' => $email]);

            if ($usuarioExistente) {
                $error_message = "El correo electrónico ya está registrado.";
            } else {
                // Guardar el nuevo usuario en la base de datos
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $resultado = $coleccionUsuarios->insertOne([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'fecha_registro' => new MongoDB\BSON\UTCDateTime()
                ]);

                if ($resultado->getInsertedCount() > 0) {
                    $success_message = "Usuario registrado exitosamente.";
                } else {
                    $error_message = "Error al registrar el usuario. Inténtalo de nuevo.";
                }
            }
        } catch (Exception $e) {
            $error_message = "Error al procesar la solicitud: " . $e->getMessage();
        }
    }
}

// Incluir la vista de registro de nuevo para mostrar mensajes
header( 'registro.php'); // Asegúrate de que el archivo se llame correctamente y esté en la ubicación correcta
?>

