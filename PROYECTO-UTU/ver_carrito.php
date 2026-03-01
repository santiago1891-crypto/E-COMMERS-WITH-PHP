<?php

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';


if (!$con) {
    echo "<tr><td colspan='5'>Error de conexión a la base de datos.</td></tr>";
    exit;
}

if (!isset($_SESSION['ID_usuario'])) {
    echo "<tr><td colspan='5'>Usuario no logueado.</td></tr>";
    exit;
}

$id_usuario = intval($_SESSION['ID_usuario']);

// Obtener los productos del carrito
$sql_productos = 
"
SELECT 
    cp.ID_producto,
    c.ID_carrito,
    p.nombre_producto,
    cp.cantidad,
    p.precio_unitario,
    (cp.cantidad * p.precio_unitario) AS subtotal
FROM Carrito c
JOIN Compra co ON c.ID_carrito = co.ID_carrito
JOIN Usuario u ON co.ID_usuario = u.ID_usuario
JOIN Carrito_Productos cp ON c.ID_carrito = cp.ID_carrito
JOIN Productos p ON cp.ID_producto = p.ID_producto
WHERE u.ID_usuario = $id_usuario;
";
$res = $con->query($sql_productos);

$total = 0;
while ($row = $res->fetch_assoc()) {
    $subtotal = $row['precio_unitario'] * $row['cantidad'];
    $total += $subtotal;
    echo "<tr>
        <td>{$row['ID_producto']}</td>
        <td>{$row['nombre_producto']}</td>
        <td>{$row['cantidad']}</td>
        <td>$ {$row['precio_unitario']}</td>
        <td>
            <button class='btn btn-danger btn-sm' onclick='eliminarDelCarrito({$row['ID_producto']})'>Eliminar</button> 
        </td>
    </tr>";
}

// Agrega el total en un elemento oculto para que el JS lo lea
echo "<tr style='display:none'><td colspan='5'><span id='carrito-total-value'>$total</span></td></tr>";

$con->close();
?>