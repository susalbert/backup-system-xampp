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
    <title>Dashboard</title>
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
        
        /* LISTA DE USUARIOS */
        .usuarios-lista {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .usuarios-lista li {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        
        .usuarios-lista li:hover {
            background: #f9f9f9;
        }
        
        .usuarios-lista li:last-child {
            border-bottom: none;
        }
        
        .usuarios-lista strong {
            color: orangered;
            font-size: 1.1em;
        }
        
        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, #ddd, transparent);
            margin: 20px 0;
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
                <a href="dashboard.php" style="color: orangered;">Dashboard</a>
                <a href="logout.php">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <h1>🎮 Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! 🎮</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <p><strong>Miembro desde:</strong> <?php echo date('d/m/Y'); ?></p>
        </div>

        <div class="card">
            <h2>📊 Usuarios Registrados</h2>
            <?php if($usuarios->num_rows > 0): ?>
                <ul class="usuarios-lista">
                    <?php while($user = $usuarios->fetch_assoc()): ?>
                        <li>
                            <strong>🎲 <?php echo htmlspecialchars($user['nombre']); ?></strong><br>
                            📧 Email: <?php echo htmlspecialchars($user['email']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay otros usuarios registrados.</p>
            <?php endif; ?>
            
            <hr>
            <?php
            $total = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc();
            ?>
            <h3>📈 Total de usuarios en la base de datos:</h3>
            <p style="font-size: 1.5em; font-weight: bold; color: orangered;"><?php echo $total['total']; ?> usuarios registrados</p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 - Panel de Control</p>
        </div>
    </footer>
</body>
</html>