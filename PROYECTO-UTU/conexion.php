<?php
// --- Conexión a BD ---
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "tienda";  //cambiar por el nombre de la base a la que se importa

$con = new mysqli($servidor, $usuario, $clave, $bd);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
?>