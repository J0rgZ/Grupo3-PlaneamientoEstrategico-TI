<?php
// Incluimos el archivo de conexión
require '../datos/conexion.php';
session_start(); // Iniciar la sesión

// Habilitar la visualización de errores (opcional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificamos que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Las contraseñas no coinciden.";
    } else {
        try {
            // Comprobar si el usuario ya existe
            $coleccionUsuarios = $db->usuarios; // Asegúrate de que la colección se llama 'usuarios'
            $usuarioExistente = $coleccionUsuarios->findOne(['email' => $email]);

            if ($usuarioExistente) {
                $_SESSION['error_message'] = "El correo electrónico ya está registrado.";
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
                    $_SESSION['success_message'] = "Usuario registrado exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al registrar el usuario. Inténtalo de nuevo.";
                }
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al procesar la solicitud: " . $e->getMessage();
        }
    }

    // Redirigir a la página de registro usando JavaScript como alternativa
    echo "<script>window.location.href = 'registrar.php';</script>";
    exit();
}

