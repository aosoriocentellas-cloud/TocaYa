<?php
include 'conexion.php';

// 1. Consultamos las categorías y fabricantes para llenar los menús desplegables
$categorias_res = $conexion->query("SELECT * FROM categoria");
$fabricantes_res = $conexion->query("SELECT * FROM fabricante");
$query = "SELECT a.*, s.CANTIDAD FROM articulo a LEFT JOIN stock s ON a.ID = s.ID_ARTICULO";
$resultado = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Artículos Musicales</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; }
        .row { display: flex; gap: 30px; flex-wrap: wrap; }
        .col-form { flex: 1; min-width: 320px; }
        .col-tabla { flex: 2; min-width: 600px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h5 { margin-top: 0; color: #34495e; font-size: 18px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background-color: #27ae60; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #219653; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #34495e; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="container">
        <h1>🎸 Tienda de Artículos Musicales</h1>
        
        <div class="row">
            <!-- Formulario Extendido -->
            <div class="col-form">
                <div class="card">
                    <h5>Registrar Instrumento</h5>
                    <form action="insertar.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre del Artículo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Precio ($)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Subir Imagen </label>
                            <input type="url" name="imagen" class="form-control" placeholder="https://enlace-de-la-foto.com" required>
                        </div>
                        <div class="form-group">
                            <label>Categoría</label>
                            <select name="id_categoria" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <?php 
                                while($cat = $categorias_res->fetch_assoc()) {
                                    // Guardamos el ID en el value, mostramos el NOMBRE al usuario
                                    echo "<option value='".$cat['ID']."'>".$cat['NOMBRE']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fabricante / Marca</label>
                            <select name="id_fabricante" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <?php 
                                while($fab = $fabricantes_res->fetch_assoc()) {
                                    echo "<option value='".$fab['ID']."'>".$fab['NOMBRE']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label>Cantidad en Stock </label>
                          <input type="number" name="cantidad" class="form-control" placeholder="Ej. 10" min="0" required>
                        </div>

                        <button type="submit" class="btn">Guardar en Inventario</button>
                    </form>
                </div>
            </div>

            <!-- Tabla Extendida -->
            <div class="col-tabla">
                <div class="card">
                    <h5>Inventario Disponible</h5>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Instrumento</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Cat. ID</th>
                                <th>Fab. ID</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    if ($resultado && $resultado->num_rows > 0) {
        $contador = 1;
                while($fila = $resultado->fetch_assoc()) {
            $id_val     = $fila['ID']; 
            $nombre_val = $fila['NOMBRE'];
            $precio_val = $fila['PRECIO'];
            $link_img   = $fila['IMAGEN']; // Ahora vuelve a ser un texto/link
            $cat_val    = isset($fila['ID_CATEGORIA']) ? $fila['ID_CATEGORIA'] : 'N/A';
            $fab_val    = isset($fila['ID_FABRICANTE']) ? $fila['ID_FABRICANTE'] : 'N/A';
            $stock_val = isset($fila['CANTIDAD']) ? $fila['CANTIDAD'] : '0';

            // Si la casilla está vacía en la BD, asignamos una imagen genérica por defecto
            if (!empty($link_img)) {
                $imagen_val = $link_img;
            } else {
                $imagen_val = "https://placeholder.com";
            }

            echo "<tr>";
            echo "<td>" . $contador . "</td>";
            echo "<td><img src='" . $imagen_val . "' class='img-preview'></td>";
            echo "<td>" . $nombre_val . "</td>";
            echo "<td>$" . number_format((float)$precio_val, 2) . "</td>";
            echo "<td>" . $stock_val . " u.</td>";
            echo "<td>" . $cat_val . "</td>";
            echo "<td>" . $fab_val . "</td>";
                                                echo "<td style='text-align: center;'>
                                            <!-- Botón de Editar con estilo -->
                                            <a href='?editar_id=" . $id_val . "' 
                                               style='color: #3498db; text-decoration: none; font-weight: bold; margin-right: 10px;'>
                                               ✏️
                                            </a>
                                            <!-- Botón de Eliminar existente -->
                                            <a href='eliminar.php?id=" . $id_val . "' 
                                               style='color: #e74c3c; text-decoration: none; font-weight: bold;'
                                               onclick='return confirm(\"¿Seguro?\")'>❌</a>
                                          </td>";
            echo "</tr>";
            $contador++;
        
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No hay instrumentos registrados.</td></tr>";
    }
    ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- CÓDIGO DE LA VENTANA EMERGENTE PARA EDITAR -->
    <?php
    if (isset($_GET['editar_id'])) {
        $id_editar = $_GET['editar_id'];
        // Buscamos los datos actuales de ese artículo específico
       $res_edit = $conexion->query("SELECT a.NOMBRE, a.PRECIO, s.CANTIDAD FROM articulo a LEFT JOIN stock s ON a.ID = s.ID_ARTICULO WHERE a.ID = $id_editar");
        if ($res_edit && $res_edit->num_rows > 0) {
            $datos_edit = $res_edit->fetch_assoc();
    ?>
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div style="background: white; padding: 30px; border-radius: 8px; width: 400px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <h5 style="margin-top: 0; color: #2c3e50;">Modificar Registro</h5>
            <p><strong>Instrumento:</strong> <?php echo $datos_edit['NOMBRE']; ?></p>
            
            <form action="actualizar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id_editar; ?>">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nuevo Precio ($)</label>
                    <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $datos_edit['PRECIO']; ?>" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Nueva Cantidad en Stock</label>
                    <input type="number" name="cantidad" class="form-control" value="<?php echo $datos_edit['CANTIDAD']; ?>" required>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn" style="background-color: #3498db; flex: 1;">Actualizar</button>
                    <a href="index.php" class="btn" style="background-color: #95a5a6; text-decoration: none; text-align: center; flex: 1; padding-top: 10px; box-sizing: border-box;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <?php 
        }
    } 
    ?>
</body>
</html>
