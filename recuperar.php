<?php
// recuperar.php - Solicitar recuperacion de contraseña
require_once 'config.php';

// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Ingresa tu email";
    } else {
        // Buscar usuario
        $stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            // Generar token unico
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Guardar token
            $stmt = $conn->prepare("INSERT INTO recuperacion (user_id, token, expira) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $token, $expira);
            $stmt->execute();

            // Enviar correo
            $mail = new PHPMailer(true);
            
try {
    // Configurar servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'u9asd8hyasd@gmail.com';
    $mail->Password = 'jlhj betr ptyl zsba';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Configurar destinatario
    $mail->setFrom('u9asd8hyasd@gmail.com', '=?UTF-8?B?' . base64_encode('Mi Proyecto Web') . '?=');
    $mail->addAddress($email, $user['nombre']);
    
    // Contenido del correo
    $mail->isHTML(false);
    $mail->Subject = '=?UTF-8?B?' . base64_encode('Recuperar tu contraseña') . '?=';
    $link = "http://localhost/mi_proyecto/reset_password.php?token=" . $token;
    $mail->Body = "Hola " . $user['nombre'] . ",\n\n";
    $mail->Body .= "Haz clic en el siguiente enlace para cambiar tu contraseña:\n\n";
    $mail->Body .= $link . "\n\n";
    $mail->Body .= "El enlace expira en 1 hora.\n\n";
    $mail->Body .= "Si no solicitaste esto, ignora este mensaje.";

    $mail->send();
    $success = "Se ha enviado un enlace a tu correo electronico. Revisa tu bandeja de entrada.";
} catch (Exception $e) {
    $error = "No se pudo enviar el correo. Error: " . $mail->ErrorInfo;
}
        } else {
            $error = "❌ No existe una cuenta con ese email";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
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
        <div class="formulario">
            <h2>🔐 Recuperar Contraseña</h2>

            <?php if($error): ?>
                <div class="alert alert-error">❌ <?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
                <div class="enlace">
                    <a href="login.php">← Volver al inicio de sesión</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>📧 Email</label>
                        <input type="email" name="email" placeholder="tu@email.com" required>
                    </div>
                    <button type="submit" class="btn">Enviar enlace al correo</button>
                </form>
                <div class="enlace">
                    <a href="login.php">← Volver al login</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Recuperar Contraseña</p>
        </div>
    </footer>
</body>
</html>