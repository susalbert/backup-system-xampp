<?php
// index.php - Pagina principal
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Mi Proyecto Web</div>
            <nav>
                <a href="index.php">Inicio</a>
                <?php if(isset($_SESSION['usuario'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Cerrar Sesion</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="registro.php">Registro</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h1>Bienvenido a Mi Proyecto Web</h1>
            <p>Este es un sistema de gestion de usuarios con registro y login.</p>
            <p>Puedes registrarte y acceder para ver la lista de usuarios.</p>
        </div>

        <div class="card">
            <h2>¿Que puedes hacer?</h2>
            <ul>
                <li>Registrarte como nuevo usuario</li>
                <li>Iniciar sesion con tu cuenta</li>
                <li>Ver la lista de usuarios registrados</li>
                <li>Cerrar sesion</li>
            </ul>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Sistema de Backup con XAMPP</p>
        </div>
    </footer>
</body>
</html>