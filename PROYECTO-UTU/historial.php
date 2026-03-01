<?php

if (!isset($_SESSION['ID_usuario'])) {
    die("<tr><td colspan='4' class='text-danger text-center'>Debes iniciar sesión</td></tr>");
}


include 'conexion.php';

$ID_usuario = $_SESSION['ID_usuario'];

$sql = "SELECT ID_compra, precio_total, fecha_compra 
        FROM Compra 
        WHERE ID_usuario = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $ID_usuario);
$stmt->execute();
$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "
        <tr>
                <td>{$fila['ID_compra']}</td>
                <td>" . number_format($fila['precio_total'], 2) . " </td>
                <td>{$fila['fecha_compra']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center text-muted'>No tienes compras registradas</td></tr>";
}

$stmt->close();
$con->close();



?>