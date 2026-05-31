<?php 
// 1. Conectamos a la base de datos
include("conexion.php"); 

// 2. LÓGICA PHP: Guardar artículo y stock si se presiona el botón
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre_articulo'];
    $precio = $_POST['precio_articulo'];
    $cantidad = $_POST['cantidad_articulo'];
    $imagen = $_POST['imagen_articulo'];

    if (!empty($nombre) && !empty($precio)) {
        // Guardamos en la tabla articulo (valores por defecto 1 para categorías y fabricantes)
        $sql = "INSERT INTO articulo (NOMBRE, PRECIO, IMAGEN, ID_CATEGORIA, ID_FABRICANTE) 
                VALUES ('$nombre', '$precio', '$imagen', 1, 1)";
        
        if ($conexion->query($sql) === TRUE) {
            // Obtenemos el ID asignado automáticamente al artículo recién creado
            $nuevo_id = $conexion->insert_id;
            
            // Guardamos la cantidad en la tabla stock vinculándola con el ID del artículo
            $sql_stock = "INSERT INTO stock (ID_ARTICULO, CANTIDAD) VALUES ($nuevo_id, '$cantidad')";
            $conexion->query($sql_stock);
        }
        
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TocaYa | Tienda de Música</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- CABECERA -->
    <header class="header">
        <div class="logo">🎸 TocaYa</div>
        <nav class="nav">
            <a href="#" class="active">Inicio</a>
            <a href="#">Artículos</a>
            <a href="#">Contacto</a>
        </nav>
    </header>

    <!-- PORTADA -->
    <section class="hero">
        <div class="hero-content">
            <h1>Tienda de Instrumentos y Música</h1>
            <p>Visualiza tu catálogo real .</p>
        </div>
    </section>

    <!-- CONTENEDOR DE DOS COLUMNAS -->
    <div class="store-container">
        
        <!-- COLUMNA 1: FORMULARIO COMPLETO -->
        <div class="form-box">
            <h3>Agregar Nuevo Artículo</h3>
            <form action="index.php" method="POST">
                <div class="input-group">
                    <label>Nombre del Artículo:</label>
                    <input type="text" name="nombre_articulo" placeholder="Ej. Batería Electrónica" required>
                </div>
                <div class="input-group">
                    <label>Precio ($):</label>
                    <input type="number" step="0.01" name="precio_articulo" placeholder="Ej. 1200" required>
                </div>
                <div class="input-group">
                    <label>Cantidad en Stock:</label>
                    <input type="number" name="cantidad_articulo" placeholder="Ej. 10" min="0" required>
                </div>
                <div class="input-group">
                    <label>Enlace de Imagen o Base64:</label>
                    <input type="text" name="imagen_articulo" placeholder="Ej. https://enlace-imagen.jpg">
                </div>
                <button type="submit" name="agregar" class="btn-submit">Añadir al Inventario</button>
            </form>
        </div>

        <!-- COLUMNA 2: LISTA DESDE LA TABLA ARTICULO -->
        <div class="products-box">
            <h3>Artículos Disponibles</h3>
            <div class="products-grid">
                <?php
                // Consulta limpia con columnas explícitas en MAYÚSCULAS
                $resultado = $conexion->query("SELECT ID, NOMBRE, PRECIO, IMAGEN FROM articulo ORDER BY ID DESC");

                if ($resultado && $resultado->num_rows > 0) {
                    while($articulo = $resultado->fetch_assoc()) {
                        
                        $id_prod = $articulo['ID'];
                        $nombre_prod = $articulo['NOMBRE'];
                        $precio_prod = $articulo['PRECIO'];
                        $imagen_prod = $articulo['IMAGEN'];

                        echo "<div class='product-card'>";
                        
                        // Validar y pintar la imagen
                        if (!empty($imagen_prod)) {
                            echo "<img src='" . $imagen_prod . "' alt='" . htmlspecialchars($nombre_prod) . "' class='product-img'>";
                        } else {
                            echo "<div class='no-img-placeholder'>📦 Sin Imagen</div>";
                        }

                        echo "<h4>" . htmlspecialchars($nombre_prod) . "</h4>";
                        echo "<p class='price'>$" . htmlspecialchars($precio_prod) . "</p>";
                        
                        // Botones de acción directos vinculados a tus archivos actualizar y eliminar
                        echo "<div class='product-actions'>";
                        echo "  <a href='actualizar.php?id=" . $id_prod . "' class='btn-action btn-edit'>✏️ Editar</a>";
                        echo "  <a href='eliminar.php?id=" . $id_prod . "' class='btn-action btn-delete' onclick='return confirm(\"¿Deseas eliminarlo?\")'>🗑️ Eliminar</a>";
                        echo "</div>";
                        
                        echo "</div>";
                    }
                } else {
                    echo "<p class='no-products'>No se encontraron artículos. ¡Añade el primero!</p>";
                }
                ?>
            </div>
        </div>

    </div>

    <!-- PIE DE PÁGINA -->
    <footer class="footer">
        <p>&copy; 2026 TocaYa. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
