<?php
include 'conexion.php';

// Recibimos los datos modificados desde el modal de la interfaz
$id = $_POST['id'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];

// 1. Actualizamos el precio forzando el nombre de la columna exacta en la tabla 'articulo'
$sql_articulo = "UPDATE articulo SET PRECIO = '$precio' WHERE ID = $id";

if ($conexion->query($sql_articulo) === TRUE) {
    
    // 2. Verificamos si este artículo específico ya posee un registro físico en la tabla stock
    $check_stock = $conexion->query("SELECT * FROM stock WHERE ID_ARTICULO = $id");
    
    if ($check_stock && $check_stock->num_rows > 0) {
        // Si el registro ya existe, actualizamos su volumen de stock actual
        $sql_stock = "UPDATE stock SET CANTIDAD = '$cantidad' WHERE ID_ARTICULO = $id";
    } else {
        // Si es un instrumento antiguo que no poseía stock, lo insertamos por primera vez
        $sql_stock = "INSERT INTO stock (ID_ARTICULO, CANTIDAD) VALUES ('$id', '$cantidad')";
    }
    
    // 3. Ejecutamos la consulta correspondiente para la tabla stock
    if ($conexion->query($sql_stock) === TRUE) {
        // Redireccionamos automáticamente a la interfaz con la información fresca
        header("Location: index.php");
        exit(); // Finaliza el script de forma limpia
    } else {
        echo "Error al guardar en la tabla stock: " . $conexion->error;
    }
} else {
    echo "Error al actualizar la tabla articulo: " . $conexion->error;
}

$conexion->close();
?>
