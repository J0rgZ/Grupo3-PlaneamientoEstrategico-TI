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
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validación básica
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Por favor, completa todos los campos.";
        header('Location: login.php?error=1');
        exit();
    }

    try {
        // Comprobar las credenciales
        $coleccionUsuarios = $db->usuarios; // Asegúrate de que la colección se llama 'usuarios'
        $usuario = $coleccionUsuarios->findOne(['email' => $email]);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Iniciar sesión y redirigir a main.php
            $_SESSION['user_id'] = $usuario['_id'];
            $_SESSION['username'] = $usuario['username']; // Almacenar el nombre de usuario si es necesario
            header('Location: index.php');
            exit();
        } else {
            // Credenciales incorrectas
            $_SESSION['error_message'] = "Credenciales incorrectas. Intenta nuevamente.";
            header('Location: login.php?error=1');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error al procesar la solicitud: " . $e->getMessage();
        header('Location: login.php?error=1');
        exit();
    }
}
