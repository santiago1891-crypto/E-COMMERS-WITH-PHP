<?php


include 'conexion.php';


$sql = "SELECT 
            co.ID_compra,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            co.metodo_pago,
            co.fecha_compra,
            co.precio_total,
            co.ID_carrito
        FROM Compra co
        JOIN Usuario u ON co.ID_usuario = u.ID_usuario";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ID_compra'] . "</td>";
        echo "<td>" . $row['nombre_usuario'] . " " . $row['apellido_usuario'] . "</td>";
        echo "<td>" . $row['fecha_compra'] . "</td>";
        echo "<td>" . $row['precio_total'] . "</td>";
        echo "<td>" . $row['metodo_pago'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<table border='1'>";
    echo "<tr><td colspan='6'>No hay compras registradas.</td></tr>";
    echo "</table>";
}

$con->close();

?>