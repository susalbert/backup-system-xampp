<?php
// reset_password.php - Cambiar contraseña con token
require_once 'config.php';

$error = '';
$success = '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    die("Token no válido");
}

// Verificar token
$stmt = $conn->prepare("SELECT r.user_id, r.expira, u.email, u.nombre FROM recuperacion r JOIN usuarios u ON r.user_id = u.id WHERE r.token = ? AND r.usado = 0");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Token no válido o ya usado");
}

$row = $result->fetch_assoc();
$expira = strtotime($row['expira']);
$now = time();

if ($now > $expira) {
    die("El enlace ha expirado. Solicita uno nuevo.");
}

$user_id = $row['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($password)) {
        $error = "Ingresa una contraseña";
    } elseif ($password != $confirm) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 4) {
        $error = "La contraseña debe tener al menos 4 caracteres";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar contraseña
        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $user_id);
        $stmt->execute();

        // Marcar token como usado
        $stmt = $conn->prepare("UPDATE recuperacion SET usado = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $success = "✅ Contraseña actualizada correctamente. Ahora puedes iniciar sesión.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Contraseña</title>
    <link rel="icon" href="media/icono.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Fredoka', sans-serif !important;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            margin: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        h1 { font-size: 2.5em; margin-bottom: 10px; }
        h2 { font-size: 1.8em; }
        p { font-size: 1em; }
        
        button, .btn {
            font-size: 1em;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 8px rgba(255, 69, 0, 0.3);
            color: white;
            background: linear-gradient(135deg, orangered, #ff6600);
            cursor: pointer;
            font-family: 'Fredoka', sans-serif;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            width: 100%;
        }
        
        button:hover, .btn:hover {
            background: linear-gradient(135deg, #ff6600, #e65c00);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 69, 0, 0.4);
        }
        
        .container {
            max-width: 1400px;
            margin: auto;
            padding: 0 20px;
        }

        /* HEADER */
        header {
            background-color: rgb(245,245,245);
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        header .logo {
            margin: 0;
            padding: 25px 30px;
            font-weight: bold;
            color: orangered;
            font-size: 1.6em;
        }
        
        header .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        header nav {
            display: flex;
            flex-direction: column;
            text-align: center;
            padding-bottom: 25px;
        }
        
        header a {
            padding: 5px 12px;
            text-decoration: none;
            font-weight: bold;
            color: black;
            font-family: 'Fredoka', sans-serif;
        }
        
        header a:hover {
            color: orangered;
        }
        
        @media (min-width: 720px) {
            header .container {
                flex-direction: row;
                justify-content: space-between;
            }
            
            header nav {
                flex-direction: row;
                padding-bottom: 0;
                padding-right: 20px;
            }
            
            body {
                padding-top: 80px;
            }
        }
        
        /* FORMULARIO - Estilo XtremeGaming */
        .formulario {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid orangered;
        }
        
        .formulario h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-family: 'Fredoka', sans-serif;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Fredoka', sans-serif;
            font-size: 1em;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: orangered;
            box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.1);
        }
        
        /* Alertas */
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-family: 'Fredoka', sans-serif;
            font-weight: 500;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        /* Enlaces */
        .enlace {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }
        
        .enlace a {
            color: orangered;
            text-decoration: none;
            font-weight: 500;
        }
        
        .enlace a:hover {
            text-decoration: underline;
        }
        
        /* Mensaje de error fatal */
        .error-fatal {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #dc3545;
            max-width: 500px;
            margin: 40px auto;
        }
        
        .error-fatal p {
            margin: 0;
            color: #721c24;
            font-size: 1.1em;
        }
        
        /* FOOTER */
        footer {
            background-color: rgb(230,230,230);
            padding: 40px 0;
            margin-top: 60px;
            text-align: center;
        }
        
        footer p {
            color: rgb(100,100,100);
            margin: 0;
            font-size: 0.9em;
        }
        
        footer .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .formulario {
                padding: 25px;
                margin: 20px auto;
            }
            
            h2 {
                font-size: 1.5em;
            }
        }
        
        body, h1, h2, h3, h4, h5, h6, p, span, a, button, input, textarea, select, label, li, ul, ol, div {
            font-family: 'Fredoka', sans-serif !important;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Web</div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="login.php">Login</a>
                <a href="registro.php">Registro</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php
        // Mostrar error si el token no es válido
        if (empty($token) || $result->num_rows == 0 || $now > $expira):
        ?>
            <div class="error-fatal">
                <p>❌ <?php 
                    if (empty($token) || $result->num_rows == 0) echo "Token no válido o ya usado";
                    elseif ($now > $expira) echo "El enlace ha expirado. Solicita uno nuevo.";
                ?></p>
                <p style="margin-top: 15px;"><a href="recuperar.php" style="color: orangered;">Solicitar nuevo enlace</a></p>
            </div>
        <?php else: ?>
            <div class="formulario">
                <h2>🔐 Nueva Contraseña</h2>

                <?php if($error): ?>
                    <div class="alert alert-error">❌ <?php echo $error; ?></div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success">✅ <?php echo $success; ?></div>
                    <div class="enlace">
                        <a href="login.php">🔑 Iniciar sesión</a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>🔒 Nueva Contraseña</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="form-group">
                            <label>🔒 Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn">Actualizar Contraseña</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Restablecer Contraseña</p>
        </div>
    </footer>
</body>
</html>