<?php
include 'conexion.php';

$sql = "SELECT ID_producto, nombre_producto, precio_unitario, stock FROM Productos";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ID_producto'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre_producto']) . "</td>";
        echo "<td>$" . number_format($row['precio_unitario'], 2) . "</td>";
        echo "<td>" . $row['stock'] . "</td>";
        // Botón Editar relacionado al ID del producto
        echo "<td><a class='btn  btn-primary' href='editar_producto.php?id_producto=" . $row['ID_producto'] . "' class='btn btn-warning btn-sm'>Editar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No hay productos registrados</td></tr>";
}
?>