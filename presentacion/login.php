<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema de Usuarios</title>

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

        .login-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 380px;
            transform: translateY(50px);
            animation: fadeIn 0.5s ease-out forwards;
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
            position: relative;
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
            padding-left: 50px;
        }

        .form-control:focus {
            border-color: #4b6fe0;
            box-shadow: 0 0 8px rgba(75, 111, 224, 0.4);
            outline: none;
        }

        .form-group .input-group-text {
            background-color: #f0f4f7;
            border: 1px solid #e1e8f1;
            border-radius: 8px 0 0 8px;
            color: #4b6fe0;
            padding: 10px 15px;
            font-size: 1.1rem;
            width: 60px;
        }

        /* Estilo para el botón de mostrar/ocultar contraseña */
        .input-group-text .toggle-password {
            cursor: pointer;
            color: #4b6fe0;
        }

        .btn-primary {
            background-color: #4b6fe0;
            border-color: #4b6fe0;
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
            border-color: #4057d0;
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

        /* Animaciones en el icono de error */
        .error-message i {
            margin-right: 5px;
            animation: shake 0.5s ease infinite alternate;
        }

        @keyframes shake {
            0% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(5px);
            }
        }

        /* Mejoras para el focus y efectos visuales en los campos */
        .form-control:focus + .input-group-text {
            background-color: #e1e8f1;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1><i class="fa fa-sign-in-alt"></i> Iniciar sesión</h1>

        <!-- Mostrar mensaje de error si las credenciales son incorrectas -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><i class="fa fa-exclamation-circle"></i> Credenciales incorrectas. Intenta nuevamente.</p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <!-- Campo de correo -->
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
                    <span class="input-group-text toggle-password" onclick="togglePassword()"><i class="fa fa-eye"></i></span>
                </div>
            </div>
            
            <!-- Botón de submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
        </form>

        <!-- Enlace de registro -->
        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias de Bootstrap (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var passwordIcon = document.querySelector('.toggle-password i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>