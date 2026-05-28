<?php
include 'conexion.php';

// Recibimos los datos del formulario (incluyendo la cantidad)
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];
$id_categoria = $_POST['id_categoria'];
$id_fabricante = $_POST['id_fabricante'];
$cantidad = $_POST['cantidad']; // Nueva variable de cantidad

// 1. Insertamos primero el artículo en su tabla
$sql_articulo = "INSERT INTO articulo (NOMBRE, PRECIO, IMAGEN, ID_CATEGORIA, ID_FABRICANTE) 
                 VALUES ('$nombre', '$precio', '$imagen', '$id_categoria', '$id_fabricante')";

if ($conexion->query($sql_articulo) === TRUE) {
    // 2. Conseguimos el ID exacto que MySQL le acaba de asignar a este artículo
    $ultimo_id = $conexion->insert_id;
    
    // 3. Insertamos ese ID y la cantidad en la tabla 'stock'
    // Asegúrate de que las columnas en tu tabla stock se llamen exactamente así (ID_ARTICULO, CANTIDAD)
    $sql_stock = "INSERT INTO stock (ID_ARTICULO, CANTIDAD) VALUES ('$ultimo_id', '$cantidad')";
    
    if ($conexion->query($sql_stock) === TRUE) {
        // Si ambos se guardan con éxito, volvemos a la pantalla principal
        header("Location: index.php");
    } else {
        echo "Error al registrar el stock: " . $conexion->error;
    }
} else {
    echo "Error al registrar el artículo: " . $conexion->error;
}

$conexion->close();
?>

