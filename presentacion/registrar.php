<?php
session_start(); // Iniciar la sesión
$error_message = !empty($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = !empty($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

// Limpiar mensajes de sesión
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Usuarios</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            background: linear-gradient(135deg, #6e7aef, #4b6fe0);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.5s ease-out forwards;
            text-align: center; /* Centra el contenido */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #4b6fe0;
            margin-bottom: 20px;
            font-size: 2rem;
            text-align: center;
            font-weight: 700;
        }

        h1 i {
            font-size: 2rem;
            margin-right: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            height: 45px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #e1e8f1;
            transition: border-color 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #4b6fe0;
            box-shadow: 0 0 8px rgba(75, 111, 224, 0.4);
            outline: none;
        }

        .btn-primary {
            background-color: #4b6fe0;
            border-radius: 8px;
            padding: 12px 15px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4057d0;
        }

        .error-message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        .login-footer a {
            color: #4b6fe0;
            text-decoration: none;
            font-weight: bold;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">

        <h1><i class="fa fa-user-plus"></i> Registrarse</h1>
        <!-- Contenedor de la animación Lottie -->
        <div id="lottie-animation" style="width: 100%; height: 280px; margin-bottom: 20px;"></div>
        
        

        <!-- Mostrar mensajes de error o éxito -->
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="controlador_registrar.php" method="POST">
            <!-- Campo de nombre de usuario -->
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Nombre de usuario" required>
                </div>
            </div>

            <!-- Campo de correo electrónico -->
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
            </div>

            <!-- Campo de contraseña -->
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
            </div>

            <!-- Campo de confirmación de contraseña -->
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
                </div>
            </div>

            <!-- Botón de submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>

        <div class="login-footer">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js"></script>

    <!-- Inicializa la animación Lottie -->
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../lottie/register.json' // Cambia esto por la ruta a tu archivo JSON
        });
    </script>
</body>
</html>
