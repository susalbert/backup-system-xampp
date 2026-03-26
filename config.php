<?php
// config.php - Conexion a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "proyecto_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

session_start();
?>