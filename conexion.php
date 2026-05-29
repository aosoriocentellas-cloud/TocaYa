<?php
$servidor = "sql113.infinityfree.com";
$usuario  = "if0_42043642";
$password = "SUI6jgin23mB"; 
$base_datos = "if0_42043642_TocaYa"; 

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
