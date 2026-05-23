# 🍷 Bodega Sauce S.A — E-Commerce Web

Proyecto de tienda online desarrollado en PHP y MySQL para la venta de vinos de la Bodega Sauce S.A. Permite a los usuarios registrarse, explorar el catálogo, agregar productos al carrito y finalizar compras con generación de factura en PDF. Incluye un panel de administración completo.

---

## 📋 Descripción General

El sistema cuenta con dos roles diferenciados:

- **Usuario común:** puede registrarse, iniciar sesión, ver productos, gestionar su carrito, editar su perfil, consultar su historial de compras y finalizar compras con pago por tarjeta y descarga de factura en PDF.
- **Administrador:** accede a un panel donde puede gestionar usuarios (agregar, eliminar), visualizar compras realizadas y administrar el inventario de productos (editar nombre, precio y stock).

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Uso |
|---|---|
| PHP | Lógica del servidor, sesiones, acceso a BD |
| MySQL | Base de datos relacional |
| HTML5 / CSS3 | Estructura y estilos de la interfaz |
| Bootstrap 5 | Componentes UI (navbar, modales, formularios) |
| SweetAlert2 | Alertas y confirmaciones interactivas |
| FPDF | Generación de facturas en PDF |
| JavaScript (Fetch API) | Carga dinámica de datos sin recarga de página |

---

## 🗂️ Estructura del Proyecto

```
PROYECTO-UTU/
│
├── index.html               # Página pública (inicio, historia, tienda, login/registro)
├── index_usuario.php        # Vista principal del usuario logueado
├── index_admin.php          # Panel de administración
│
├── login.php                # Autenticación de usuarios
├── registro.php             # Registro de usuarios comunes
├── registro_admin.php       # Registro de usuarios desde el panel admin
├── cerrar_sesion.php        # Destruye la sesión y redirige al inicio
│
├── agregar_producto.php     # Agrega un producto al carrito del usuario
├── eliminar_producto.php    # Elimina un producto del carrito
├── editar_producto.php      # Edita nombre, precio y stock de un producto (admin)
│
├── eliminar_usuario.php     # Elimina un usuario (admin)
├── editar_perfil.php        # Permite al usuario editar sus datos personales
│
├── finalizar_compra.php     # Procesa el pago, genera factura y descarga PDF
├── historial.php            # Retorna el historial de compras del usuario (AJAX)
│
├── conexion.php             # Configuración de conexión a la base de datos
├── bd.sql                   # Script SQL para crear y poblar la base de datos
│
├── style.css                # Estilos para la página pública
├── style_usario.css         # Estilos para la vista del usuario logueado
├── style-admin.css          # Estilos para el panel de administración
├── style_editar.css         # Estilos para formularios de edición
│
└── imagenes/                # Logos, fotos de la bodega y productos
```

---

## 🗃️ Base de Datos

La base de datos se llama `tienda` y contiene las siguientes tablas principales:

- **Usuario** — datos personales y credenciales de todos los usuarios.
- **Usuario_Administrativo** — subtipo con permisos de administrador.
- **Usuario_Comun** — subtipo para clientes regulares.
- **Productos** — catálogo con nombre, precio y stock.
- **Carrito** — carrito de compra con fecha y total.
- **Carrito_Productos** — relación N:N entre carrito y productos, con cantidad.
- **Compra** — registro de cada compra asociada a un usuario y un carrito.
- **Factura** — factura generada al finalizar una compra (relación 1:1 con Compra).
- **TarjetaTemporal** — almacena temporalmente los datos de tarjeta durante el proceso de pago y se elimina al completar la transacción.

---

## ⚙️ Instalación y Configuración

### Requisitos previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web local (XAMPP, Laragon, WAMP, etc.)

### Pasos

1. **Clonar o copiar** el proyecto en la carpeta `htdocs` (XAMPP) o equivalente de tu servidor.

2. **Importar la base de datos:**
   - Abrir phpMyAdmin.
   - Crear una base de datos llamada `tienda`.
   - Importar el archivo `bd.sql`.

3. **Configurar la conexión** en `conexion.php`:

```php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";          // tu contraseña de MySQL
$bd       = "tienda";   // nombre de la base de datos importada
```

4. **Instalar FPDF** para la generación de facturas:
   - Descargar desde [fpdf.org](http://www.fpdf.org/).
   - Colocar la carpeta `fpdf/` en la raíz del proyecto.

5. Abrir el navegador y acceder a `http://localhost/PROYECTO-UTU/`.

---

## 👥 Roles y Acceso

| Rol | Página de entrada | Contraseña |
|---|---|---|
| Usuario común | `index_usuario.php` | Hasheada con `password_hash()` |
| Administrador | `index_admin.php` | Texto plano en BD |

> ⚠️ Se recomienda implementar hashing también para las contraseñas de administradores en un entorno de producción.

---

## ✨ Funcionalidades Principales

**Clientes:**
- Registro e inicio de sesión con validación de roles.
- Visualización del catálogo de vinos con carrusel.
- Carrito de compras persistente con actualización de cantidades.
- Proceso de pago con datos de tarjeta (almacenamiento temporal).
- Generación y descarga automática de factura en PDF.
- Historial de compras.
- Edición de datos del perfil.

**Administradores:**
- Panel con tablas de usuarios, compras y productos cargadas dinámicamente.
- Registro de nuevos usuarios (comunes o administradores).
- Eliminación de usuarios con confirmación (SweetAlert2).
- Edición de productos (nombre, precio, stock).

---

## 📄 Licencia

Proyecto académico desarrollado para la UTU (Universidad del Trabajo del Uruguay).  
© 2025 BitsMaster — Todos los derechos reservados.
