<?php
// Conectamos a la base de datos
include 'conexion.php';

// Verificamos si llegó el ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL corregida con ID en mayúsculas
    $sql = "DELETE FROM articulo WHERE ID = $id";

    if ($conexion->query($sql) === TRUE) {
        // Redireccionar de vuelta al index si todo sale bien
        header("Location: index.php");
        exit();
    } else {
        echo "Error al eliminar el registro: " . $conexion->error;
    }
}

// Cerramos la conexión
$conexion->close();
?>
