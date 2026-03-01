<?php

include 'conexion.php';


if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}


// Recibe los datos del formulario
$nombre_usuario = $_POST['nombre_usuario'];
$contraseña = $_POST['contraseña'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$ciudad = $_POST['ciudad'];
$calle = $_POST['calle'];
$n_puerta = $_POST['n_puerta'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$rol = $_POST['rol'];

// Construye la dirección
$direccion = $calle . ' ' . $n_puerta . ', ' . $ciudad;

// Hashea la contraseña si no es administrador
if ($rol === 'Administrador') {
    $contrasena_guardar = $contraseña;
} else {
    $contrasena_guardar = password_hash($contraseña, PASSWORD_DEFAULT);
}

// Inserta el usuario
$sql_usuario = "INSERT INTO Usuario (nombre, apellido, email, telefono, direccion, n_puerta, calle, ciudad, n_usuario, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql_usuario);
$stmt->bind_param("ssssssssss", $nombre, $apellido, $email, $telefono, $direccion, $n_puerta, $calle, $ciudad, $nombre_usuario, $contrasena_guardar);

if ($stmt->execute()) {
    $id_usuario = $stmt->insert_id;

    // Inserta en la tabla de rol correspondiente
    if ($rol === 'Administrador') {
        $sql_rol = "INSERT INTO Usuario_Administrativo (ID_usuario, permisos) VALUES (?, 'Administrador')";
        $stmtRol = $con->prepare($sql_rol);
        $stmtRol->bind_param("i", $id_usuario);
        $stmtRol->execute();
        $stmtRol->close();
    } else {
        $sql_rol = "INSERT INTO Usuario_Comun (ID_usuario) VALUES (?)";
        $stmtRol = $con->prepare($sql_rol);
        $stmtRol->bind_param("i", $id_usuario);
        $stmtRol->execute();
        $stmtRol->close();
    }

    echo 
    "
    <div class='mensaje_registro'>
        <h1 class='h1_aviso'>Usuario Correctamente Registrados</h1>
    </div>
    
    
    ";
    echo "<script>
        setTimeout(function() {
            window.location.href = 'index_admin.html';
        }, 2000); // 2000 ms = 2 segundos
    </script>";
} else {
    echo "<div class='alert alert-danger'>Error al registrar usuario: " . $stmt->error . "</div>";
}

$stmt->close();
$con->close();
?>