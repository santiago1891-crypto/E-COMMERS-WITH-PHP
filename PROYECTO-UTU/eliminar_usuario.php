<?php

include 'conexion.php';

if (isset($_POST['id_usuario'])) {
    $id = intval($_POST['id_usuario']);

    // Eliminar de las lq tablas Usuario_Administrativo y Usuario_Comun
    $con->query("DELETE FROM Usuario_Administrativo WHERE ID_usuario = $id");
    $con->query("DELETE FROM Usuario_Comun WHERE ID_usuario = $id");

    // Luego eliminar de Usuario(tabla raiz)
    if ($con->query("DELETE FROM Usuario WHERE ID_usuario = $id") === TRUE) {
        echo "ok";
    } else {
        echo "Error al eliminar: " . $con->error;
    }
}

$con->close();
?>
