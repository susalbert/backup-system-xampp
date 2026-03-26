<?php
// dashboard.php - Panel de control
require_once 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Obtener lista de todos los usuarios (excepto el actual)
$stmt = $conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id != ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$usuarios = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Mi Proyecto Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Mi Proyecto Web</div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Cerrar Sesion</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        </div>

        <div class="card">
            <h2>Usuarios Registrados</h2>
            <?php if($usuarios->num_rows > 0): ?>
                <ul class="usuarios-lista">
                    <?php while($user = $usuarios->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($user['nombre']); ?></strong><br>
                            Email: <?php echo htmlspecialchars($user['email']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay otros usuarios registrados.</p>
            <?php endif; ?>
            
            <hr>
            <h3>Total de usuarios en la base de datos:</h3>
            <?php
            $total = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc();
            echo "<p>Hay " . $total['total'] . " usuarios registrados en total.</p>";
            ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Sistema de Backup con XAMPP</p>
        </div>
    </footer>
</body>
</html>