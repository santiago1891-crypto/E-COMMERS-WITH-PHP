<?php
include 'conexion.php';

$sql = "SELECT ID_producto, nombre_producto, precio_unitario FROM Productos";
$result = $con->query($sql);

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $total = count($productos);
    $primero = true;
    for ($i = 0; $i < $total; $i += 3) {
        echo '<div class="carousel-item' . ($primero ? ' active' : '') . '">';
        echo '<section class="productos">';
        for ($j = $i; $j < $i + 3 && $j < $total; $j++) {
            $img_src = "imagenes/producto_" . $productos[$j]['ID_producto'] . ".jpg";
            echo '<div class="producto">';
            echo '<img class="img_tienda" src="' . $img_src . '" alt="' . htmlspecialchars($productos[$j]['nombre_producto']) . '">';
            echo '<h2>' . htmlspecialchars($productos[$j]['nombre_producto']) . '</h2>';
            echo '<span class="precio">$' . number_format($productos[$j]['precio_unitario'], 2) . '</span>';
            // Botón que abre la modal de iniciar sesión
            echo '<button type="button" class="btn-nav btn-primary" data-bs-toggle="modal" data-bs-target="#modal_ingreso">Agregar al carrito</button>';
            echo '</div>';
        }
        echo '</section>';
        echo '</div>';
        $primero = false;
    }
} else {
    echo '<div class="carousel-item active"><section class="productos"><p>No hay productos disponibles.</p></section></div>';
}
?>