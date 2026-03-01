<?php

include 'conexion.php';

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

$nombre_usuario = $_POST['nombre_usuario'];
$contraseña = $_POST['contraseña'];

//informacion personal
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$ciudad = $_POST['ciudad'];
$calle = $_POST['calle'];
$n_puerta = $_POST['n_puerta'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];



// Hashear la contraseña
$contrasena_hash = password_hash($contraseña, PASSWORD_DEFAULT);

// check si el usuario ya existe
$sql_check_user = "SELECT * FROM Usuario WHERE n_usuario = ?";
$stmt_check = $con->prepare($sql_check_user);
$stmt_check->bind_param("s", $nombre_usuario);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // usuario existe
    header("Location: index.html?registro=existe");
    exit;
} else {
    // Insertar en Usuario
    $sql_usuario = "INSERT INTO Usuario (nombre, apellido, email, telefono, direccion, n_puerta, calle, ciudad, n_usuario, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql_usuario);
    $stmt->bind_param("ssssssssss", $nombre, $apellido, $email, $telefono, $direccion, $n_puerta, $calle, $ciudad, $nombre_usuario, $contrasena_hash);

    if ($stmt->execute()) {
        $id_usuario = $stmt->insert_id;

        // Insertar en Usuario_Comun
        $sql_comun = "INSERT INTO Usuario_Comun (ID_usuario) VALUES (?)";
        $stmt_comun = $con->prepare($sql_comun);
        $stmt_comun->bind_param("i", $id_usuario);

        if ($stmt_comun->execute()) {
            header("Location: index_usuario.php");
            exit;

        } else {
            echo "Error al insertar en Usuario_Comun: " . $stmt_comun->error;
        }

        $stmt_comun->close();
    } else {
        echo "Error al insertar en Usuario: " . $stmt->error;
    }

    $stmt->close();
}

$stmt_check->close();
mysqli_close($con);
?>