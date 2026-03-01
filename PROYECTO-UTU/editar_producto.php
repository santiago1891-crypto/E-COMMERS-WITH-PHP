<?php
include 'conexion.php';
session_start();

// Obtener el ID del producto
if (!isset($_GET['id_producto'])) {
    die("ID de producto no especificado.");
}
$id_producto = intval($_GET['id_producto']);

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre_producto'] ?? '';
    $precio = $_POST['precio_unitario'] ?? '';
    $stock = $_POST['stock'] ?? '';

    $sql = "UPDATE Productos SET nombre_producto=?, precio_unitario=?, stock=? WHERE ID_producto=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdii", $nombre, $precio, $stock, $id_producto);

    if ($stmt->execute()) {
        $mensaje = "Producto actualizado correctamente ✅";
    } else {
        $mensaje = "Error al actualizar: " . $con->error;
    }
    $stmt->close();
}

// Obtener datos actuales del producto
$sql = "SELECT * FROM Productos WHERE ID_producto=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
$stmt->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="icon" href="imagenes/logo_redondeado.png" type="image/png">
    <link rel="stylesheet" href="style_editar.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<div  class="contenedor">
     <nav class=" navbar navbar-expand-lg bg.light">
            <div class="container-fluid ">
            <img  src="imagenes/logo.png" alt="Logo"  class="img-logo d-inline-block align-text-top">
            <h2 class="title nav-item">Editar Producto</h2>
                <div class="d-flex">
                        <a href="index_admin.php">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="carrito_svg" viewBox="0 0 16 16">
                            <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                        </svg>
                    </a>
                </div>
        </nav>
    </head>
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre_producto" class="form-control" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario" class="form-control" value="<?= htmlspecialchars($producto['precio_unitario']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($producto['stock']) ?>" required>
        </div>
        <button type="submit" class="btn btn-actualizar">Actualizar</button>
    </form>
</div>
</body>
   
</html>