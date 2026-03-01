<?php
session_start();

include 'conexion.php';

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['ID_usuario']) || empty($_SESSION['ID_usuario'])) {
    echo "Error: Usuario no autenticado.";
    $con->close();
    exit;
}

// Verifica que los datos del producto estén presentes
if (!isset($_POST['id_producto']) || !isset($_POST['cantidad'])) {
    echo "Error: Datos de producto incompletos.";
    $con->close();
    exit;
}

$id_usuario = intval($_SESSION['ID_usuario']);
$id_producto = intval($_POST['id_producto']);
$cantidad = intval($_POST['cantidad']);

// -----------------------------
// 1. Buscar carrito activo del usuario
// -----------------------------
$sql_carrito = "
    SELECT c.ID_carrito
    FROM Carrito c
    JOIN Compra co ON c.ID_carrito = co.ID_carrito
    WHERE co.ID_usuario = $id_usuario AND co.metodo_pago = 'pendiente'
    ORDER BY c.ID_carrito DESC
    LIMIT 1
";

$result = $con->query($sql_carrito);

if ($result->num_rows > 0) {
    // Carrito ya existente
    $row = $result->fetch_assoc();
    $id_carrito = $row['ID_carrito'];
} else {
    // Crear nuevo carrito
    $con->query("INSERT INTO Carrito (fecha, total) VALUES (NOW(), 0)");
    $id_carrito = $con->insert_id;

    // Asociarlo al usuario en la tabla Compra
    $con->query("INSERT INTO Compra (ID_usuario, metodo_pago, fecha_compra, precio_total, ID_carrito) 
                VALUES ($id_usuario, 'pendiente', NOW(), 0, $id_carrito)");
}

// -----------------------------
// 2. Insertar producto en carrito
// -----------------------------
$sql_check = "
SELECT cantidad 
FROM Carrito_Productos 
WHERE ID_carrito = $id_carrito AND ID_producto = $id_producto
";

$res_check = $con->query($sql_check);

if ($res_check->num_rows > 0) {
    // Si el producto ya estaba, actualizamos cantidad
    $con->query("
        UPDATE Carrito_Productos 
        SET cantidad = cantidad + $cantidad 
        WHERE ID_carrito = $id_carrito AND ID_producto = $id_producto
    ");
} else {
    // Si no estaba, lo insertamos
    $con->query("
        INSERT INTO Carrito_Productos (ID_carrito, ID_producto, cantidad) 
        VALUES ($id_carrito, $id_producto, $cantidad)
    ");
}

// -----------------------------
// 3. Recalcular total del carrito
// -----------------------------
$sql_total = "
SELECT SUM(cp.cantidad * p.precio_unitario) AS total
FROM Carrito_Productos cp
JOIN Productos p ON cp.ID_producto = p.ID_producto
WHERE cp.ID_carrito = $id_carrito
";

$res_total = $con->query($sql_total);
$total = ($res_total->num_rows > 0) ? $res_total->fetch_assoc()['total'] : 0;

// Actualizar total en Carrito y en Compra
$con->query("UPDATE Carrito SET total = $total WHERE ID_carrito = $id_carrito");
$con->query("UPDATE Compra SET precio_total = $total WHERE ID_carrito = $id_carrito");

// -----------------------------
// 4. Confirmación
// -----------------------------
echo "✅ Producto agregado al carrito con éxito.";
$con->close();
exit;
?>