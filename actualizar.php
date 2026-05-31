<?php
include 'conexion.php';

// --- PASO 1: CAPTURAR EL ID QUE VIENE DESDE EL INDEX (MÉTODO GET) ---
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

// --- PASO 2: LÓGICA DE GUARDADO (MÉTODO POST) ---
if (isset($_POST['guardar_cambios'])) {
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // 1. Actualizamos el precio en la tabla articulo
    $sql_articulo = "UPDATE articulo SET PRECIO = '$precio' WHERE ID = $id";

    if ($conexion->query($sql_articulo) === TRUE) {
        
        // 2. Verificamos si este artículo ya posee registro en la tabla stock
        $check_stock = $conexion->query("SELECT * FROM stock WHERE ID_ARTICULO = $id");
        
        if ($check_stock && $check_stock->num_rows > 0) {
            // Modificamos a CANTIDAD en mayúsculas por si acaso
            $sql_stock = "UPDATE stock SET CANTIDAD = '$cantidad' WHERE ID_ARTICULO = $id";
        } else {
            $sql_stock = "INSERT INTO stock (ID_ARTICULO, CANTIDAD) VALUES ('$id', '$cantidad')";
        }
        
        // 3. Ejecutamos la consulta de stock
        if ($conexion->query($sql_stock) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error en tabla stock: " . $conexion->error;
        }
    } else {
        echo "Error en tabla articulo: " . $conexion->error;
    }
}

// --- PASO 3: OBTENER LOS DATOS ACTUALES PARA MOSTRARLOS EN EL FORMULARIO ---
$consulta_articulo = $conexion->query("SELECT NOMBRE, PRECIO FROM articulo WHERE ID = $id");
$articulo = $consulta_articulo->fetch_assoc();

$consulta_stock = $conexion->query("SELECT CANTIDAD FROM stock WHERE ID_ARTICULO = $id");
$stock = ($consulta_stock && $consulta_stock->num_rows > 0) ? $consulta_stock->fetch_assoc() : ['CANTIDAD' => 0];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo | TocaYa</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header class="header">
        <div class="logo">🎸 TocaYa</div>
        <nav class="nav"><a href="index.php">Volver al Inicio</a></nav>
    </header>

    <div class="store-container" style="justify-content: center; margin-top: 50px;">
        <div class="form-box" style="max-width: 500px; width: 100%;">
            <h3>✏️ Editar: <?php echo htmlspecialchars($articulo['NOMBRE']); ?></h3>
            
            <form action="actualizar.php" method="POST">
                <!-- Campo oculto para enviar el ID de vuelta -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="input-group">
                    <label>Nuevo Precio ($):</label>
                    <input type="number" step="0.01" name="precio" value="<?php echo $articulo['PRECIO']; ?>" required>
                </div>

                <div class="input-group">
                    <label>Cantidad en Stock:</label>
                    <input type="number" name="cantidad" value="<?php echo $stock['CANTIDAD'] ?? $stock['cantidad'] ?? 0; ?>" required>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" name="guardar_cambios" class="btn-submit">Guardar Cambios</button>
                    <a href="index.php" class="btn-action" style="background: #cbd5e1; color: #334155; line-height: 2.5;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
