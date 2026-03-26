<?php
// registro.php - Registro de nuevos usuarios
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validar campos
    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } elseif ($password != $confirm) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 4) {
        $error = "La contraseña debe tener al menos 4 caracteres";
    } else {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Este email ya esta registrado";
        } else {
            // Encriptar contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $hash);

            if ($stmt->execute()) {
                $success = "Registro exitoso. Ya puedes iniciar sesion.";
            } else {
                $error = "Error al registrar. Intenta de nuevo.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Mi Proyecto Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Mi Proyecto Web</div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="login.php">Login</a>
                <a href="registro.php">Registro</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="formulario">
            <h2>Registro de Usuario</h2>

            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Registrarse</button>
            </form>
            <p style="text-align:center; margin-top:15px;">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesion</a>
            </p>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Sistema de Backup con XAMPP</p>
        </div>
    </footer>
</body>
</html>