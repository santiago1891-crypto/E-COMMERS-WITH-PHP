<?php

include 'conexion.php';


if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$sql=
"
SELECT 
    u.ID_usuario,
    u.nombre,
    u.email,
    CASE
        WHEN ua.ID_usuario IS NOT NULL THEN 'Administrador'
        WHEN uc.ID_usuario IS NOT NULL THEN 'Común'
        ELSE 'Sin Rol'
    END AS rol
FROM Usuario u
LEFT JOIN Usuario_Administrativo ua ON u.ID_usuario = ua.ID_usuario
LEFT JOIN Usuario_Comun uc ON u.ID_usuario = uc.ID_usuario
";

$result = $con->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID_usuario']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rol']) . "</td>";

        // Columna de acciones (puedes personalizar los botones)
        echo "
<td>
    <button type='button' onclick='eliminarUsuario(" . $row['ID_usuario'] . ")'>Eliminar</button>
</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No hay usuarios registrados.</td></tr>";
}

mysqli_close($con);
?>