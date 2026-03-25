<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto Web</title>
</head>
<body>
    <h1>Bienvenido a mi proyecto web</h1>
    
    <?php
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "proyecto_db");
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    // Consultar usuarios
    $sql = "SELECT nombre, email FROM usuarios";
    $resultado = $conn->query($sql);
    
    // Verificar si la consulta falló
    if ($resultado === false) {
        echo "Error en la consulta: " . $conn->error;
    } elseif ($resultado->num_rows > 0) {
        echo "<h2>Lista de usuarios:</h2>";
        echo "<ul>";
        while($fila = $resultado->fetch_assoc()) {
            echo "<li>" . $fila["nombre"] . " - " . $fila["email"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No hay usuarios registrados.";
    }
    
    $conn->close();
    ?>
</body>
</html>