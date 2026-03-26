<?php
// index.php - Pagina principal
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuarios</title>
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
        h3 { font-size: 1.3em; }
        p { font-size: 1em; }
        
        button, .btn {
            font-size: 1em;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.5);
            box-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            color: white;
            background-color: orangered;
            cursor: pointer;
            font-family: 'Fredoka', sans-serif;
            text-decoration: none;
            display: inline-block;
        }
        
        button:hover, .btn:hover {
            background-color: rgba(255, 72, 0, 0.8);
            transform: translateY(-2px);
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
        
        /* CARDS - Estilo XtremeGaming */
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 4px solid orangered;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .card h1, .card h2, .card h3 {
            margin-top: 0;
            color: #333;
        }
        
        /* LISTA DE CARACTERÍSTICAS */
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        ul li {
            padding: 12px 0;
            padding-left: 30px;
            position: relative;
            border-bottom: 1px solid #f0f0f0;
        }
        
        ul li:last-child {
            border-bottom: none;
        }
        
        ul li::before {
            content: "🎮";
            position: absolute;
            left: 0;
            color: orangered;
        }
        
        /* BOTONES DE ACCIÓN */
        .acciones {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn-login {
            background: linear-gradient(135deg, orangered, #ff6600);
        }
        
        .btn-registro {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }
        
        .btn-dashboard {
            background: linear-gradient(135deg, #2196F3, #0b7dda);
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #f44336, #da190b);
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
            .card {
                padding: 20px;
            }
            
            h1 {
                font-size: 2em;
            }
            
            h2 {
                font-size: 1.5em;
            }
            
            .acciones {
                flex-direction: column;
            }
            
            .acciones .btn {
                text-align: center;
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
                <a href="index.php" style="color: orangered;">Inicio</a>
                <?php if(isset($_SESSION['usuario'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="registro.php">Registro</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <h1>🎮 Bienvenido 🎮</h1>
            <p>Tu espacio para gestionar usuarios y acceder a contenido exclusivo.</p>
            <?php if(isset($_SESSION['usuario'])): ?>
                <p style="color: orangered; font-weight: bold;">✅ Has iniciado sesión como: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>📋 ¿Qué puedes hacer?</h2>
            <ul>
                <li>Registrarte como nuevo usuario</li>
                <li>Iniciar sesión con tu cuenta</li>
                <li>Ver la lista de usuarios registrados</li>
                <li>Cerrar sesión</li>
            </ul>
            
            <div class="acciones">
                <?php if(isset($_SESSION['usuario'])): ?>
                    <a href="dashboard.php" class="btn btn-dashboard">🎲 Ir al Dashboard</a>
                    <a href="logout.php" class="btn btn-logout">🔒 Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">🔑 Iniciar Sesión</a>
                    <a href="registro.php" class="btn btn-registro">📝 Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Panel de Usuarios</p>
        </div>
    </footer>
</body>
</html>