<?php
$servidor = "TU_MYSQL_HOSTNAME_DE_INFINITYFREE";
$usuario  = "TU_MYSQL_USERNAME_DE_INFINITYFREE";
$password = "TU_MYSQL_PASSWORD_DE_INFINITYFREE"; 
$base_datos = "TU_DATABASE_NAME_DE_INFINITYFREE"; 

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
