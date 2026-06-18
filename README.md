# Fsociety — Tienda de Accesorios Gamer

Proyecto académico de e-commerce desarrollado para el curso de Sistemas Operativos.

## Acerca del proyecto

Fsociety es una tienda en línea de accesorios gamer (mouses, teclados, audífonos, mousepads, cámaras web y micrófonos) construida con Laravel. El proyecto demuestra la aplicación práctica de conceptos de Sistemas Operativos: control de concurrencia con mutex distribuido, procesamiento de trabajos en background, lectura del sistema de archivos `/proc` del kernel, y gestión de sesiones/colas/caché mediante Redis.

## Stack tecnológico

- **Backend:** Laravel 13 / PHP 8.3
- **Base de datos:** MariaDB
- **Sesiones, colas y caché:** Redis
- **Servidor web:** Nginx + PHP-FPM
- **Sistema operativo:** Debian 12

## Características principales

- Catálogo de productos con búsqueda, filtros por categoría y ordenamiento por precio
- Carrito de compras y checkout con simulación de pago
- Seguimiento de pedidos con línea de tiempo de estados
- Panel de administración: gestión de productos, pedidos y usuarios
- Monitor del sistema operativo en tiempo real (CPU, RAM, disco, red, procesos)
- Mutex distribuido con Redis para evitar condiciones de carrera en el stock al procesar compras simultáneas
- Procesamiento de pedidos en background mediante colas (jobs)
- Diseño totalmente responsive (escritorio, tablet y móvil)

## Instalación local

1. Clonar el repositorio:
```bash
   git clone <url-del-repositorio>
   cd ecommerce-gamer
```

2. Instalar dependencias:
```bash
   composer install
```

3. Configurar el entorno:
```bash
   cp .env.example .env
   php artisan key:generate
```

4. Editar `.env` con tus credenciales de base de datos y Redis.

5. Ejecutar migraciones y poblar la base de datos:
```bash
   php artisan migrate --seed
```

6. Crear el enlace de almacenamiento (para imágenes de productos):
```bash
   php artisan storage:link
```

7. Iniciar el worker de colas (necesario para procesar pedidos):
```bash
   php artisan queue:work --tries=3
```

8. Levantar el servidor de desarrollo:
```bash
   php artisan serve
```

## Requisitos previos

- PHP 8.3+
- Composer
- MariaDB o MySQL
- Redis
- Nginx o Apache (opcional para desarrollo local; `php artisan serve` es suficiente)

## Autor

Diego — Proyecto desarrollado de forma individual dentro de un grupo académico de 5 integrantes.
