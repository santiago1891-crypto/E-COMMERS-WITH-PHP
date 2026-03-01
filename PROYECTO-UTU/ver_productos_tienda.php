<?php
// filepath: c:\xampp\htdocs\PROYECTO-UTU-V1.0.0.2\ver_productos_tienda.php
include 'conexion.php';

$sql = "SELECT ID_producto, nombre_producto, precio_unitario, stock FROM Productos";
$result = $con->query($sql);

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $total = count($productos);
    $primero = true;
    for ($i = 0; $i < $total; $i += 3) {
        // Abre el carousel-item, el primero debe tener la clase 'active'
        echo '<div class="carousel-item' . ($primero ? ' active' : '') . '">';
        echo '<section class="productos">';
        for ($j = $i; $j < $i + 3 && $j < $total; $j++) {
            $prod = $productos[$j];
            $img_src = "imagenes/producto_" . $prod['ID_producto'] . ".jpg";
            $stock = (int)$prod['stock'];
            $maxQty = min(max(1, $stock), 10);
            echo '<div class="producto" data-stock="'. $stock .'" data-max="'. $maxQty .'">';
            echo '<form class="form-agregar-producto" method="post" action="agregar_producto.php">';
            echo '<input type="hidden" name="id_producto" value="' . $prod['ID_producto'] . '">';
            echo '<input type="hidden" name="stock" value="' . $stock . '">';
            echo '<img class="img_tienda" src="' . $img_src . '" alt="' . htmlspecialchars($prod['nombre_producto']) . '">';
            echo '<h2>' . htmlspecialchars($prod['nombre_producto']) . '</h2>';
            echo '<span class="precio">$' . number_format($prod['precio_unitario'], 2) . '</span>';
            echo '<input class="input_tineda" min="1" max="'. $maxQty .'" data-max="'. $maxQty .'" type="number" name="cantidad" value="1" required>';
            echo '<button type="submit" class="btn-comprar">Agregar al carrito</button>';
            echo '<div class="stock-info">Stock: ' . $stock . '</div>';
            echo '</form>';
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