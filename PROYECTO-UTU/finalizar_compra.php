<?php
session_start();
include 'conexion.php';
require('fpdf/fpdf.php');

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (!isset($_SESSION['ID_usuario'])) {
    die("Error: No has iniciado sesión.");
}



$ID_usuario = $_SESSION['ID_usuario'];

// Obtener nombre y apellido del usuario
$sql_user = "SELECT nombre, apellido FROM Usuario WHERE ID_usuario = ?";
$stmt_user = $con->prepare($sql_user);
$stmt_user->bind_param("i", $ID_usuario);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$user = $res_user->fetch_assoc();
$nombre_completo = $user ? $user['nombre'] . ' ' . $user['apellido'] : 'Usuario';


// ===================== TARJETA TEMPORAL =====================
// Verificar que los datos de tarjeta llegaron del formulario
if (!empty($_POST['numero_tarjeta']) && !empty($_POST['vencimiento']) && !empty($_POST['cvv'])) {
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $vencimiento = $_POST['vencimiento'];
    $cvv = $_POST['cvv'];

    // Insertar tarjeta temporal
    $sql = "INSERT INTO TarjetaTemporal (ID_usuario, numero, vencimiento, cvv) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isss", $ID_usuario, $numero_tarjeta, $vencimiento, $cvv);
    $stmt->execute();
    $stmt->close();
} else {
    die("⚠️ Error: Debes ingresar los datos de la tarjeta.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['metodo_pago'])) {
    $metodo_pago = $_POST['metodo_pago'];

    // Buscar compra pendiente
    $sql = "SELECT ID_compra, ID_carrito FROM Compra WHERE ID_usuario = ? AND metodo_pago = 'pendiente' ORDER BY ID_compra DESC LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ID_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        die("No tienes compras activas.");
    }

    $ID_compra = $row['ID_compra'];
    $ID_carrito = $row['ID_carrito'];

    // Calcular total y obtener productos
    $sql = "SELECT cp.ID_producto, cp.cantidad, p.nombre_producto, p.precio_unitario
            FROM Carrito_Productos cp
            JOIN Productos p ON cp.ID_producto = p.ID_producto
            WHERE cp.ID_carrito = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ID_carrito);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;
    $productos = [];
    while ($prod = $result->fetch_assoc()) {
        $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
        $total += $subtotal;
        $productos[] = $prod;

        // Restar stock
        $sql_update = "UPDATE Productos SET stock = stock - ? WHERE ID_producto = ?";
        $stmt_upd = $con->prepare($sql_update);
        $stmt_upd->bind_param("ii", $prod['cantidad'], $prod['ID_producto']);
        $stmt_upd->execute();
        $stmt_upd->close();
    }
    $stmt->close();

    // Actualizar compra
    $sql = "UPDATE Compra SET metodo_pago = ?, precio_total = ?, fecha_compra = NOW() WHERE ID_compra = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdi", $metodo_pago, $total, $ID_compra);
    $stmt->execute();
    $stmt->close();

    // Crear factura
    $sql = "INSERT INTO Factura (ID_compra, factura_total, fecha_factura) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("id", $ID_compra, $total);
    $stmt->execute();
    $stmt->close();

    // Vaciar carrito
    $con->query("DELETE FROM Carrito_Productos WHERE ID_carrito = $ID_carrito");
    $con->query("DELETE FROM Carrito WHERE ID_carrito = $ID_carrito");


    // ===================== ELIMINAR TARJETA TEMPORAL =====================
$sql = "DELETE FROM TarjetaTemporal WHERE ID_usuario = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $ID_usuario);
$stmt->execute();
$stmt->close();


    // --- PDF---
    class PDF extends FPDF {
        function Header() {
            // Logo en la parte superior derecha
            $this->Image('imagenes/logo_redondeado.png', 170, 10, 30);
            $this->SetFont('Arial','B',18);
            $this->SetTextColor(95, 28, 28);
            $this->Cell(0,10,'FACTURA',0,1,'L');
            $this->Ln(5);
        }

        function Footer() {
            // Posición: 25 mm desde el final
            $this->SetY(-30);
            // Línea superior ligera
            $this->SetDrawColor(200,200,200);
            $this->SetLineWidth(0.2);
            $this->Line(10, $this->GetY(), $this->w - 10, $this->GetY());
            $this->Ln(4);
            // Mensaje en el footer
            $this->SetFont('Arial','I',9);
            $this->SetTextColor(0,0,0);
            $mensaje = 'Su pedido será entregado en 10 días hábiles, por consultas llame al 099500147';
            $this->Cell(0,8,utf8_decode($mensaje),0,0,'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->SetFillColor(248, 246, 242);

    // Fecha
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(0,6,'Fecha: '. date("d / m / Y"),0,1);
    $pdf->Ln(2);

    // Nombre y apellido del comprador
    $pdf->SetFont('Arial','B',15);
    $pdf->SetTextColor(178, 34, 34);
    $pdf->Cell(50,6,'Titular de la cuenta:',0,0);
    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(0,6,utf8_decode($nombre_completo),0,1);
    $pdf->Ln(5);

    // Método de pago
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,6, utf8_decode('Método de pago: ').utf8_decode($metodo_pago),0,1);
    $pdf->Ln(5);

    // Encabezado de tabla (solo borde inferior)
    $pdf->SetTextColor(178, 34, 34);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(20,10,'CANT.', 'B', 0, 'C');
    $pdf->Cell(100,10,'DESCRIPCION', 'B', 0, 'C');
    $pdf->Cell(35,10,'PRECIO UNIT.', 'B', 0, 'C');
    $pdf->Cell(35,10,'IMPORTE', 'B', 1, 'C');

    // Filas de productos (solo borde inferior)
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',12);
    foreach($productos as $prod){
        $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
        $pdf->Cell(20,10,$prod['cantidad'],'B',0,'C');
        $pdf->Cell(100,10,utf8_decode($prod['nombre_producto']),'B',0,'L');
        $pdf->Cell(35,10,'$'.number_format($prod['precio_unitario'],2),'B',0,'R');
        $pdf->Cell(35,10,'$'.number_format($subtotal,2),'B',1,'R');
    }

    // Subtotales y total
    $pdf->Cell(120,8,'',0);
    $pdf->Cell(35,8,'Subtotal','B');
    $pdf->Cell(35,8,'$'.number_format($total,2),'B',1,'R');



    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(120,8,'',0);
    $pdf->Cell(35,8,'TOTAL','B');
    $pdf->Cell(35,8,'$'.number_format($total, 2).' ','B',1,'R');

    $pdf->Output('D', 'factura_compra.pdf');
    exit;
} else {
    echo "⚠️ Error: No se seleccionó un método de pago.";
}

$con->close();
exit;
?>
