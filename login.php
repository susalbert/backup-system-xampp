<?php
// login.php - Inicio de sesion
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Buscar usuario por email
        $stmt = $conn->prepare("SELECT id, nombre, email, contraseña FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['contraseña'])) {
                $_SESSION['usuario'] = $user['nombre'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Email no registrado";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Mi Proyecto Web</title>
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
            <h2>Iniciar Sesion</h2>

            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">Iniciar Sesion</button>
            </form>
            <p style="text-align:center; margin-top:15px;">
                ¿No tienes cuenta? <a href="registro.php">Registrate</a>
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