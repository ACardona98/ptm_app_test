# Instrucciones para Configurar y Levantar Prueba Técnica

## 1. Instalación de XAMPP o MAMP
Asegúrate de tener XAMPP instalado en tu sistema. Puedes descargarlo desde el sitio oficial de Apache Friends.

- **a.** Validar los puertos de Apache y MySQL.
- **b.** Levantar el servidor dando clic en `Start`.

## 2. Clona este repositorio
Clona este repositorio desde GitHub y colócalo en la carpeta `htdocs` del servidor Apache

## 3. Crear la Base de Datos

Para crear la base de datos, en el repositorio, en la ruta `/scripts/` se incluyó el archivo `ptm_test_db.sql`, que contiene los scripts necesarios para crear la base de datos y la tabla `productos`.

Si se requiere modificar la conexión a la base de datos, ve al archivo `/app/config/Database.php` y modifica los atributos de `$username` y `$password`:

```php
private $username = 'root';
private $password = 'root';
```
## 4. Reiniciar el servicio apache
## 5. Acceder al módulo de productos
Una vez realizados estos pasos ya podemos acceder al módulo de productos que incluye todas las funcionalidades CRUD y demás requerimientos de la prueba técnica http://localhost/ptm_app_test/public/templates/productos.php
