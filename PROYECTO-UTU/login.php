<?php
session_start();
include 'conexion.php';

if (!$con) {
    // No imprimir nada antes de header; redirigir o morir
    header('Location: index.html?login=error_db');
    exit;
}

if (isset($_POST['iniciar_sesion'])) {
    $nombre = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contraseña'] ?? '';

    // consulta a la base de datos 
    $sql = "SELECT * FROM Usuario WHERE n_usuario = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            $id = $usuario['ID_usuario'];

            // Determinar si es admin o común
            $esAdmin = $con->query("SELECT 1 FROM Usuario_Administrativo WHERE ID_usuario = $id")->num_rows > 0;
            $esComun = $con->query("SELECT 1 FROM Usuario_Comun WHERE ID_usuario = $id")->num_rows > 0;

            $valido = false;

            if ($esAdmin) {
                // Contraseña en texto plano (solo si así está en la BD)
                if ($contrasena === $usuario['contrasena']) {
                    $valido = true;
                }
            } elseif ($esComun) {
                // Contraseña encriptada
                if (password_verify($contrasena, $usuario['contrasena'])) {
                    $valido = true;
                }
            }

            if ($valido) {
                $_SESSION['nombre'] = $usuario['n_usuario'];
                $_SESSION['ID_usuario'] = $usuario['ID_usuario'];

                if ($esAdmin) {
                    header("Location: index_admin.php");
                    exit;
                } elseif ($esComun) {
                    header("Location: index_usuario.php");
                    exit;
                } else {
                    // rol no asignado
                    header("Location: index.html?login=no_role");
                    exit;
                }
            } else {
                // contraseña incorrecta
                header("Location: index.html?login=wrong_password");
                exit;
            }
        } else {
            // usuario no encontrado
            header("Location: index.html?login=user_not_found");
            exit;
        }
    } else {
        header("Location: index.html?login=error_query");
        exit;
    }
}

$con->close();
exit;
?>
