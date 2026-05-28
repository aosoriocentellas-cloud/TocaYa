<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "musica"; 

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Comprobar si hay error
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

echo "¡Conexión exitosa a la base de datos!";
?>
