<?php
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bodega Online - Venta de Vinos</title>
    <link rel="stylesheet" href="style_usario.css">
    <link rel="icon" href="imagenes/logo_redondeado.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Agregar SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>


<!--Barra de navegacion-->
<nav class="navbar navbar-expand-lg fixed-top  bg.light">
    <div class="container-fluid">
        <img src="imagenes/logo.png" alt="Logo" class="img-logo d-inline-block align-text-top">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>       
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-light" href="#inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="#about">Nuestra Historia</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="#tienda">Tienda</a></li>
                <li class="nav-item">
                    <button type="button" class="btn-nav btn-primary" onclick="window.location.href='cerrar_sesion.php'">Cerrar Sesion</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn-nav btn-primary" data-bs-toggle="modal" data-bs-target="#modal_historial">Ver historial de compras</button>
                </li>
            </ul>
        </div>
        <div class="container-fluid d-flex justify-content-end">

            <!--Carrito -->
            <button type="button" class="btn-carrito" data-bs-toggle="modal" data-bs-target="#modal_carrito">
                <svg class="carrito_svg" xmlns="http://www.w3.org/2000/svg" width="10" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
            </button>

            <!--Usuario -->
            <div>
                <a class="a_usuario" target="_blank" href="editar_perfil.php">
                    <svg class="carrito-svg" xmlns="http://www.w3.org/2000/svg" color="white" width="10" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
                </a>
            </div>
        </div>
    </div>
</nav>

<main>
<!--Modal historial de compras-->
<div class="modal fade" id="modal_historial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Historial de Compras</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="carrito-th">ID Compra</th>
                            <th class="carrito-th">Total</th>
                            <th class="carrito-th">Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCompras">
                            <?php   include 'historial.php'  ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--modal carrito-->
<div class="modal fade modal-lg" id="modal_carrito" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tu carrito</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="carrito-th">id producto</th>
                        <th class="carrito-th">Producto</th>
                        <th class="carrito-th">Cantidad</th>
                        <th class="carrito-th">Precio</th>
                        <th class="carrito-th">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="carrito-items"> <!-- contenido bd --> </tbody>
            </table>
            <div class="mb-3">
                <strong>Total a pagar: $<span id="carrito-total"></span></strong>
            </div>
            
                <form   method="post" action="finalizar_compra.php"  id="form-finalizar">
                    <div class="mb-3">
                        <label for="metodo_pago">Método de pago:</label>
                        <select name="metodo_pago" class="form-select" id="metodo_pago" required>
                            <option value="">Selecciona una opción</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>

                    <fieldset>
                        <legend>Detalles de la tarjeta</legend>
                        <div class="mb-3">
                            <label for="nombre_tarjeta" class="form-label">Numero tarjeta</label>
                            <input type="number" name="numero_tarjeta" id="numero_tarjeta" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="vencimiento" class="form-label">Vencimiento de su tarjeta</label>
                            <input type="month" name="vencimiento" id="vencimiento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <div>
                                <input type="password" name="cvv" id="cvv" class="form-control" required>
                            </div>
                    </fieldset>
                    <button type="submit" class="btn-pagar">Pagar</button>
                </form>
        </div>
        </div>
    </div>
</div>



<!--HERO-->
<section class="hero_content" id="inicio">
<h2 class="title_content">SOMOS BODEGAS S.A SAUCE</h2>
<p class="p_hero">
Bienvenido a Bodegas S.A Sauce, donde la tradición y la pasión por el vino se unen para ofrecerte productos de la más alta calidad. Descubre nuestra historia, explora nuestra tienda y déjate sorprender por la excelencia de nuestros vinos artesanales.
</p>
<a class="hero_a" href="#tienda">Ir a la tienda</a>
</section>

<!--ABOUT-->
<section id="about" class="section_historia">
    <div class="historia_container">
        <div class="historia_texto">
            <h2>Nuestra Historia</h2>
                <p>
                    Bodegas S.A Sauce nació hace más de 50 años en el corazón de nuestra región, con la pasión de una familia dedicada al arte del vino. Desde nuestros inicios, hemos trabajado la tierra con respeto y dedicación, seleccionando las mejores uvas para crear vinos únicos que reflejan la esencia de nuestro terruño.<br><br>
                    A lo largo de las décadas, nuestra bodega ha crecido y evolucionado, incorporando tecnología de vanguardia sin perder la tradición que nos caracteriza. Cada botella cuenta una historia de esfuerzo, innovación y amor por el vino.<br><br>
                    Hoy, Bodegas S.A Sauce es reconocida por su calidad y compromiso, llevando nuestros productos a mesas de todo el país y el mundo. Te invitamos a conocer más sobre nuestro legado y a disfrutar de una experiencia única en cada copa.
                </p>
        </div>
        <div class="historia_imagenes">
            <img loading="lazy" src="imagenes/img-about1.png" alt="Barricas">
            <img loading="lazy" src="imagenes/img-about2.png" alt="Fanrica de la bodega">
            <img loading="lazy" src="imagenes/img-about3.png" alt="Viñedos">
        </div>
    </div>
</section>
<!--TIENDA-->
<article id="tienda" class="article_tienda">      
<h2 class="title_carrito">Carrito</h2> 
    <!--Tienda 1-->
    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner" id="productos-tienda">
        <!-- Aquí se cargan los carousel-item -->
        </div>
    <!-- Botones carrusel -->
    <button class="btn-carrusel carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="btn-carrusel carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    </div>
</article>
</main>

<!--Fotter-->
<footer>
        <div class="content_redes">
            <a target="_blank" href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="auto" preserveAspectRatio="xMidYMid" viewBox="0 0 256 256"><path fill="#fff" d="M128 23.064c34.177 0 38.225.13 51.722.745 12.48.57 19.258 2.655 23.769 4.408 5.974 2.322 10.238 5.096 14.717 9.575 4.48 4.479 7.253 8.743 9.575 14.717 1.753 4.511 3.838 11.289 4.408 23.768.615 13.498.745 17.546.745 51.723 0 34.178-.13 38.226-.745 51.723-.57 12.48-2.655 19.257-4.408 23.768-2.322 5.974-5.096 10.239-9.575 14.718-4.479 4.479-8.743 7.253-14.717 9.574-4.511 1.753-11.289 3.839-23.769 4.408-13.495.616-17.543.746-51.722.746-34.18 0-38.228-.13-51.723-.746-12.48-.57-19.257-2.655-23.768-4.408-5.974-2.321-10.239-5.095-14.718-9.574-4.479-4.48-7.253-8.744-9.574-14.718-1.753-4.51-3.839-11.288-4.408-23.768-.616-13.497-.746-17.545-.746-51.723 0-34.177.13-38.225.746-51.722.57-12.48 2.655-19.258 4.408-23.769 2.321-5.974 5.095-10.238 9.574-14.717 4.48-4.48 8.744-7.253 14.718-9.575 4.51-1.753 11.288-3.838 23.768-4.408 13.497-.615 17.545-.745 51.723-.745M128 0C93.237 0 88.878.147 75.226.77c-13.625.622-22.93 2.786-31.071 5.95-8.418 3.271-15.556 7.648-22.672 14.764C14.367 28.6 9.991 35.738 6.72 44.155 3.555 52.297 1.392 61.602.77 75.226.147 88.878 0 93.237 0 128c0 34.763.147 39.122.77 52.774.622 13.625 2.785 22.93 5.95 31.071 3.27 8.417 7.647 15.556 14.763 22.672 7.116 7.116 14.254 11.492 22.672 14.763 8.142 3.165 17.446 5.328 31.07 5.95 13.653.623 18.012.77 52.775.77s39.122-.147 52.774-.77c13.624-.622 22.929-2.785 31.07-5.95 8.418-3.27 15.556-7.647 22.672-14.763 7.116-7.116 11.493-14.254 14.764-22.672 3.164-8.142 5.328-17.446 5.95-31.07.623-13.653.77-18.012.77-52.775s-.147-39.122-.77-52.774c-.622-13.624-2.786-22.929-5.95-31.07-3.271-8.418-7.648-15.556-14.764-22.672C227.4 14.368 220.262 9.99 211.845 6.72c-8.142-3.164-17.447-5.328-31.071-5.95C167.122.147 162.763 0 128 0Zm0 62.27C91.698 62.27 62.27 91.7 62.27 128c0 36.302 29.428 65.73 65.73 65.73 36.301 0 65.73-29.428 65.73-65.73 0-36.301-29.429-65.73-65.73-65.73Zm0 108.397c-23.564 0-42.667-19.103-42.667-42.667S104.436 85.333 128 85.333s42.667 19.103 42.667 42.667-19.103 42.667-42.667 42.667Zm83.686-110.994c0 8.484-6.876 15.36-15.36 15.36-8.483 0-15.36-6.876-15.36-15.36 0-8.483 6.877-15.36 15.36-15.36 8.484 0 15.36 6.877 15.36 15.36Z"/></svg>
            </a>

            <a target="_blank" href="">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="url(#a)" height="40" width="40"><defs><linearGradient x1="50%" x2="50%" y1="97.078%" y2="0%" id="a"><stop offset="0%" stop-color="#0062E0"/><stop offset="100%" stop-color="#19AFFF"/></linearGradient></defs><path d="M15 35.8C6.5 34.3 0 26.9 0 18 0 8.1 8.1 0 18 0s18 8.1 18 18c0 8.9-6.5 16.3-15 17.8l-1-.8h-4l-1 .8z"/><path fill="#FFF" d="m25 23 .8-5H21v-3.5c0-1.4.5-2.5 2.7-2.5H26V7.4c-1.3-.2-2.7-.4-4-.4-4.1 0-7 2.5-7 7v4h-4.5v5H15v12.7c1 .2 2 .3 3 .3s2-.1 3-.3V23h4z"/></svg>
            </a>

            <a target="_blank" href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="1200" height="1227" fill="none" viewBox="0 0 1200 1227"><path fill="#fff" d="M714.163 519.284 1160.89 0h-105.86L667.137 450.887 357.328 0H0l468.492 681.821L0 1226.37h105.866l409.625-476.152 327.181 476.152H1200L714.137 519.284h.026ZM569.165 687.828l-47.468-67.894-377.686-540.24h162.604l304.797 435.991 47.468 67.894 396.2 566.721H892.476L569.165 687.854v-.026Z"/></svg>
            </a>
        </div>
        
        <p>&copy; 2025  By BitsMaster. Todos los derechos reservados.</p>
</footer>
</body>
</html>






<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Cargar productos de la tienda desde la base de datos
    function cargarProductosTienda() {
        fetch('ver_productos_tienda.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('productos-tienda').innerHTML = html;
            })
            .catch(err => console.error('Error cargando productos:', err));
    }

    // Función para cargar el carrito en la modal
    function cargarCarrito() {
        fetch('ver_carrito.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('carrito-items').innerHTML = html;
                const totalElem = document.getElementById('carrito-total-value');
                document.getElementById('carrito-total').textContent = totalElem ? totalElem.textContent : "0";
            });
    }

    // Delegación: capturar todos los submit de formularios con la clase form-agregar-producto
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form && form.classList && form.classList.contains('form-agregar-producto')) {
            e.preventDefault();

            let stockInput = form.querySelector('input[name="stock"]');
            let stock = stockInput ? parseInt(stockInput.value, 10) : NaN;
            if (isNaN(stock)) {
                const prodWrapper = form.closest('.producto');
                stock = prodWrapper ? parseInt(prodWrapper.dataset.stock, 10) : NaN;
            }

            // BLOQUEAR compra si stock <= 5
            if (!isNaN(stock) && stock <= 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: `No es posible comprar este producto. Quedan ${stock} unidades en stock y se requiere más de 5.`,
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            const cantidadInput = form.querySelector('input[name="cantidad"]');
            const cantidad = cantidadInput ? parseInt(cantidadInput.value, 10) : 1;
            if (!isNaN(stock) && cantidad > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cantidad mayor al stock',
                    text: `Solo hay ${stock} unidades disponibles.`,
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            const datos = new FormData(form);
            fetch('agregar_producto.php', {
                method: 'POST',
                body: datos
            })
            .then(response => response.text())
            .then(respuesta => {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto agregado',
                    text: 'El producto se agregó al carrito.',
                    timer: 1400,
                    showConfirmButton: false
                });
                cargarCarrito();
            })
            .catch(err => {
                console.error('Error agregar_producto:', err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo agregar el producto.' });
            });
        }
    });

    document.getElementById('productos-tienda').addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-add-product]');
        if (!btn) return;
        const form = btn.closest('form') || (btn.dataset.formSelector && document.querySelector(btn.dataset.formSelector));
        if (!form) return;
        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    });

    window.eliminarDelCarrito = function(id_producto) {
        fetch('eliminar_producto.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id_producto=' + encodeURIComponent(id_producto)
        })
        .then(() => cargarCarrito())
        .catch(err => console.error('Error eliminar_producto:', err));
    };

    const modalCarrito = document.getElementById('modal_carrito');
    if (modalCarrito) modalCarrito.addEventListener('show.bs.modal', cargarCarrito);

    cargarProductosTienda();
});
</script>