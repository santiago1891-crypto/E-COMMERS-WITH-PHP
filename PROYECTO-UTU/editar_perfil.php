<?php
session_start();

include 'conexion.php';


if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
    echo '<a href="index.html">Error de conexion,volver al inicio</a>';
}

// --- ID del usuario logueado ---
if (!isset($_SESSION['ID_usuario'])) {
    die("Debes iniciar sesión.");
}
$ID_usuario = $_SESSION['ID_usuario'];

// --- Si el formulario fue enviado ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $n_puerta = $_POST['n_puerta'] ?? '';
    $calle = $_POST['calle'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';

    $sql = "UPDATE Usuario 
            SET nombre=?, apellido=?, email=?, telefono=?, direccion=?, n_puerta=?, calle=?, ciudad=? 
            WHERE ID_usuario=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $email, $telefono, $direccion, $n_puerta, $calle, $ciudad, $ID_usuario);

    if ($stmt->execute()) {
        $mensaje = "Datos actualizados correctamente ✅";
    } else {
        $mensaje = "Error al actualizar: " . $con->error;
    }
    $stmt->close();
}

// --- Obtener datos actuales del usuario ---
$sql = "SELECT * FROM Usuario WHERE ID_usuario=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $ID_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="icon" href="imagenes/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style_editar.css">
</head>

<body class="container mt-4">
<div class="contenedor">
<head>
        <nav class=" navbar navbar-expand-lg bg.light">
            <div class="container-fluid ">
            <img  src="imagenes/logo.png" alt="Logo"  class="img-logo d-inline-block align-text-top">
            <h2 class="title nav-item">Editar perfil</h2>
                <div class="d-flex">
                        <a href="index_usuario.php">
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
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($usuario['direccion']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Número de puerta</label>
            <input type="text" name="n_puerta" class="form-control" value="<?= htmlspecialchars($usuario['n_puerta']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control" value="<?= htmlspecialchars($usuario['calle']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Ciudad</label>
            <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($usuario['ciudad']) ?>">
        </div>

        <button  type="submit" class="btn-actualizar">Actualizar</button>
    </form>
</div>
</body>
</html>
