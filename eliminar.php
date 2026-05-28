<?php
// Conectamos a la base de datos
include 'conexion.php';

// Verificamos si llegó el ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para borrar el registro según su ID
    $sql = "DELETE FROM articulo WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        // Redireccionar de vuelta al index si todo sale bien
        header("Location: index.php");
    } else {
        echo "Error al eliminar el registro: " . $conexion->error;
    }
}

// Cerramos la conexión
$conexion->close();
?>
