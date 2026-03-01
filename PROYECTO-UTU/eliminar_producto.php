<?php
session_start();

include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['ID_usuario'];

// ID del producto que queremos eliminar (viene desde un formulario o enlace)
if (isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);

    // Obtenemos el carrito más reciente asociado al usuario
    $sql_carrito = "SELECT c.ID_carrito 
                    FROM Carrito c
                    JOIN Compra co ON c.ID_carrito = co.ID_carrito
                    WHERE co.ID_usuario = ?
                    ORDER BY c.ID_carrito DESC
                    LIMIT 1";
    $stmt_carrito = $con->prepare($sql_carrito);
    $stmt_carrito->bind_param("i", $id_usuario);
    $stmt_carrito->execute();
    $result_carrito = $stmt_carrito->get_result();

    if ($row = $result_carrito->fetch_assoc()) {
        $id_carrito = $row['ID_carrito'];

        // Eliminamos el producto del carrito
        $sql_delete = "DELETE FROM Carrito_Productos WHERE ID_carrito = ? AND ID_producto = ?";
        $stmt_delete = $con->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $id_carrito, $id_producto);

        if ($stmt_delete->execute()) {
            echo "Producto eliminado del carrito.";
        } else {
            echo "Error al eliminar producto.";
        }
        $stmt_delete->close();
    } else {
        echo "No se encontró carrito para este usuario.";
    }
    $stmt_carrito->close();
} else {
    echo "No se recibió el ID del producto.";
}

$con->close();
?>
