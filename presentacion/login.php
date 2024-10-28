<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema Plan Estratégico</title>

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
            max-width: 500px;
            transform: translateY(50px);
            animation: fadeIn 1.0s ease-out forwards;
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

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        #lottie {
            width: 100px; /* Ajusta el tamaño de la animación */
            height: 100px; /* Ajusta el tamaño de la animación */
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Contenedor de la animación Lottie -->
        <h1><i class="fa fa-sign-in-alt"></i> Iniciar sesión</h1>

        <div id="lottie-animation" style="width: 100%; height: 350px; margin-bottom: 20px;"></div>
        
        <!-- Mostrar mensaje de error si las credenciales son incorrectas -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><i class="fa fa-exclamation-circle"></i> Credenciales incorrectas. Intenta nuevamente.</p>
        <?php endif; ?>

        <form action="controlador_login.php" method="POST" onsubmit="showLoader()">
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
            <p>¿No tienes cuenta? <a href="registrar.php">Regístrate aquí</a></p>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div id="lottie"></div>
    </div>

    <!-- Bootstrap JS y dependencias de Bootstrap (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js"></script>

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        // Inicializa la animación Lottie
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../lottie/login.json' // Cambia esto por la ruta a tu archivo JSON
        });

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

        function showLoader() {
            var overlay = document.getElementById('loadingOverlay');
            overlay.style.display = 'flex';

            // Inicializar Lottie para mostrar la animación
            var animation = lottie.loadAnimation({
                container: document.getElementById('lottie'),
                renderer: 'svg',
                loop: false,
                autoplay: true,
                path: '../lottie/Cargando.json' // Cambia esto por la ruta a tu archivo JSON
            });

            // Ocultar después de 4 segundos
            setTimeout(function() {
                overlay.style.display = 'none'; // Ocultar después de 4 segundos
            }, 4000);
        }
    </script>
</body>
</html>

