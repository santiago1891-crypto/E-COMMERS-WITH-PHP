<?php
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: index.html");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bodega Sauce S.A - Admin</title>
    
    <!-- Bootstrap CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    
    <!--Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style-admin.css">
    <script src="index_admin.js"></script>
    <link rel="icon" href="imagenes/logo_redondeado.png" type="image/png">

</head>
<body>
    <header class="">
    <nav class="navbar navbar-expand-lg fixed-top "style="background-color:#601c1c;">
    <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index_admin.php">
        <img src="imagenes/logo_redondeado.png" alt="Logo" class="img-logo" style="height:40px;">
        <span class="ms-2 text-light">Administración</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin" aria-controls="navAdmin" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navAdmin">
    <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link text-light" href="#usuarios">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link text-light" href="#compras">Compras</a></li>
        <li class="nav-item"><a class="nav-link text-light" href="#inventario">Productos y su gestion</a></li>
        <li class="nav-item"> <button><a class="nav-link text-light" href="cerrar_sesion.php">Cerrar sesion</a></button></li>
        </ul>
    </div>
    </div>
</nav>
    </header>

    <main class="admin_main">


<section id="usuarios" class="admin_section">

    <h2>Usuarios Registrados</h2>


<button type="button" class="btn-agregar btn-primary" data-bs-toggle="modal" data-bs-target="#ingresar_usuario" data-bs-whatever="@mdo">Agregar usuario</button>


<!-- Modal Ingresar Usuario -->
<div class="modal fade" id="ingresar_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo usuario</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form-registro-admin">
            
            <div class="mb-3">
                <label for="recipient-name" class="col-form-label">Nombre de usuario:   <strong>*</strong> </label>
                <input type="text" required name="nombre_usuario" class="form-control">
            </div>
            <div class="mb-3">
                <label for="message-text" class="col-form-label">Contraseña: <trong>*</trong></label>
                <input type="password" required name="contraseña" class="form-control">  
            </div>
            <fieldset>
                    <legend>Informacion Personal</legend>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Nombre: <strong>*</strong></label>
                        <input type="text" required name="nombre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Apellido: <trong>*</trong></label>
                        <input type="text" required name="apellido" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Ciudad: <trong>*</trong></label>
                        <input type="text" required name="ciudad" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Calle: <trong>*</trong></label>
                        <input type="text" required name="calle" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Número de puerta: <trong>*</trong></label>
                        <input type="number" name="n_puerta" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Teléfono: <trong>*</trong></label>
                        <input type="number" required name="telefono" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Email: <trong>*</trong></label>
                        <input type="email" required name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Rol: <trong>*</trong></label>
                
                        <select class="form-control" name="rol" required>
                            <option class="form-control" value="Administrador">Administrador</option>
                            <option value="Comun">Comun</option>
                        </select>
                    </div>

                <div class="modal-footer">
                        <button type="submit" class="btn-agregar btn-primary">Registrar</button>
                </div>
            </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

    


    <table id="tabla_usuarios" class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se insertarán las filas desde PHP -->
        </tbody>
    </table>
</section>

    <section id="compras" class="admin_section">
        <h2>Compras Realizadas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Compra</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Metodo de pago</th>
                </tr>
            </thead>
            <tbody id="compras-body">
                <!--aqui van tablas insertadas por php-->
            </tbody>
        </table>
    </section>

        <section id="inventario" class="admin_section">
        <h2>Catálogo  de Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="inventario-body">
                <!--aqui van tablas insertadas por php-->
            </tbody>
        </table>
    </section>
</main>


<script>

// Cargar usuarios al cargar la página
document.addEventListener('DOMContentLoaded', cargar_Usuarios);

// Cargar compras al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    fetch('ver_compras.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('compras-body').innerHTML = html;
        });
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('ver_compras.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('compras-body').innerHTML = html;
        });
});


function cargar_Usuarios() {
    fetch('ver_usuarios.php')
        .then(response => response.text())
        .then(html => {
            document.querySelector('#tabla_usuarios tbody').innerHTML = html;
        });
}


// Cargar inventario en la sección de compra
function cargarInventario() {
    fetch('ver_productos.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('inventario-body').innerHTML = html;
        });
}

// Llama a esta función cuando cargue la página o cuando quieras actualizar el inventario
document.addEventListener('DOMContentLoaded', cargarInventario);

// Función para editar producto (puedes mostrar un modal o redirigir)
function editarProducto(id_producto) {
    alert('Editar producto ID: ' + id_producto);
}

function eliminarUsuario(id)
{
    Swal.fire({
        title: "Seguro de que quieres eliminar el usuario?",
        showCancelButton: true,
        confirmButtonText: "si",
    }).then((result) => {
        if (result.isConfirmed) { 
            fetch('eliminar_usuario.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id_usuario=' + encodeURIComponent(id)
            })
            .then(response => response.text())
            .then(respuesta => {
                Swal.fire("Usuario eliminado correctamente!", "", "success");
                cargar_Usuarios(); // Recarga la tabla de usuarios sin recargar la página
            });
        }
    });
}

document.getElementById('form-registro-admin').addEventListener('submit', function(e) {
    e.preventDefault();
    const datos = new FormData(this);

    fetch('registro_admin.php', {
        method: 'POST',
        body: datos
    })
    .then(response => response.text())
    .then(respuesta => {
        
        Swal.fire
        ({
            title: "Usuario registrado correctamente!",
            icon: "success",
            draggable: true
        });

        cargar_Usuarios(); // Recarga la tabla de usuarios
        
    });
});


// Cargar productos más vendidos
function cargarMasVendidos() {
    fetch('ver_productos_mas_vendidos.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('mas-vendidos-body').innerHTML = html;
        })
        .catch(err => {
            console.error('Error cargando productos más vendidos:', err);
            document.getElementById('mas-vendidos-body').innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar datos.</td></tr>';
        });
}
</script>
</body>
</html>