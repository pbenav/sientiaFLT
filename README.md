# sientiaFLT — Sistema de Gestión de Flota y Reservas de Vehículos

**Sientia Open Source Lab** — Software libre para equipos y empresas.

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL_v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4+-777BB4)](https://www.php.net/)
[![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20)](https://laravel.com/)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-3-FFB200)](https://filamentphp.com/)

---

## 📌 ¿Qué es sientiaFLT?

sientiaFLT es un sistema completo de **gestión de flota y reservas de vehículos** de código abierto, pensado para empresas de alquiler de vehículos, flotas corporativas y servicios de movilidad. Te permite gestionar vehículos, categorías, reservas, clientes, pagos, facturas, ubicaciones y mucho más desde un panel de administración intuitivo y potente.

Forma parte del ecosistema **Sientia Open Source Lab**, donde desarrollamos herramientas gratuitas para que cualquier persona, equipo o empresa pueda gestionarse mejor. Sin costes de licencia. Sin limitaciones.

### 🧩 Ecosistema Sientia

| Aplicación | Descripción | Demo |
|---|---|---|
| **sientiaCTH** | Control y registro de la jornada laboral | [cth.sientia.com](https://cth.sientia.com) |
| **sientiaERP** | ERP empresarial: facturación, almacén, clientes | [erp.sientia.com](https://erp.sientia.com) |
| **sientiaMTX** | Planificación de recursos basada en matrices | [mtx.sientia.com](https://mtx.sientia.com) |
| **sientiaFLT** | Gestión de flota y reservas de vehículos | Esta aplicación |

---

## ✨ Características

### Gestión de Vehículos
- Catálogo completo de vehículos con fotos, características y estado
- Categorías de vehículos con precios diferenciados por período y volumen
- Períodos de precio (temporada alta/baja) con tarifas dinámicas
- Descuentos por volumen (más días = mejor precio)
- Control de estado: disponible, en reserva, en mantenimiento, fuera de servicio
- Asignación a ubicaciones y seguimiento de ubicación

### Reservas y Reservas
- Motor de reservas con calendario visual
- Cálculo automático de precios según categoría, períodos y descuentos por volumen
- Estados: pendiente, confirmada, activa, completada, cancelada
- Fechas de recogida y devolución con ubicación
- Control de conductor (edad, adicionales)
- Historial completo de reservas

### Gestión de Clientes
- Perfiles completos con datos de contacto y fiscales
- Historial de reservas y transacciones
- Segmentación y búsqueda avanzada

### Pagos y Facturación
- Gestión de pagos parciales y totales
- Facturación automática con generación de PDF
- Control de depósitos y garantías
- Estados de pago: no pagado, parcial, pagado, reembolsado
- Soporte multi-moneda

### Gestión de Reseñas
- Sistema de valoraciones de clientes
- Aprobación de reseñas
- Valoración de estrellas

### Panel de Administración
- Panel de administración completo con FilamentPHP
- Dashboard con estadísticas en tiempo real
- Búsqueda y filtros avanzados
- Exportación de datos
- Gestión multi-usuario con roles

### Características Técnicas
- Interfaz moderna y responsive
- Validación de datos en tiempo real
- Notificaciones y alertas
- Soporte para múltiples ubicaciones
- API REST para integraciones

---

## 🏗️ Arquitectura

| Capa | Tecnología |
|---|---|
| **Backend** | Laravel 12 (PHP 8.4+) |
| **Frontend** | Livewire 3 + Alpine.js |
| **Admin Panel** | FilamentPHP 3 |
| **Estilos** | TailwindCSS |
| **Base de datos** | MySQL / MariaDB |
| **Autenticación** | Laravel Breeze |

---

## 📦 Requisitos del sistema

- **PHP** 8.4 o superior
- **MySQL** 8.0+ o MariaDB 10.6+
- **Composer** 2.7+
- **Node.js** 20+ (con npm o yarn)
- **Servidor web** Nginx o Apache con soporte de reescritura

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/pbenav/sientiaFLT.git
cd sientiaFLT
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js y compilar assets

```bash
npm install && npm run build
```

### 4. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con los datos de tu base de datos y configuración:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sientiaflt
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto creará todas las tablas y poblará datos iniciales de ejemplo, incluyendo:
- Categorías de vehículos con períodos de precio y descuentos por volumen
- Vehículos de muestra (scooters)
- Usuario administrador por defecto

### 6. Crear el enlace de almacenamiento simbólico

```bash
php artisan storage:link
```

### 7. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

### 8. Acceder al panel de administración

Ve a `/admin` en tu navegador. Accede con las credenciales del usuario administrador creado por el seeder.

---

## 🔧 Configuración avanzada

### Producción con Nginx

```nginx
server {
    listen 80;
    server_name flota.tudominio.com;
    root /var/www/sientiaFLT/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Tareas programadas (Cron)

Configura el cron del servidor para ejecutar las tareas programadas de Laravel cada minuto:

```cron
* * * * * cd /var/www/sientiaFLT && php artisan schedule:run >> /dev/null 2>&1
```

### Colas de trabajo

Para procesar colas en segundo plano (emails, notificaciones, etc.):

```bash
php artisan queue:work --queue=high,default --tries=3
```

O usa el servicio de systemd para mantener el worker activo.

---

## 📂 Estructura del proyecto

```
sientiaFLT/
├── app/
│   ├── Filament/          # Panel de administración (Resources, Pages, Widgets)
│   │   ├── Resources/     # Resources para cada entidad (Vehicles, Bookings, etc.)
│   │   └── RelationManagers/ # Relaciones entre entidades
│   ├── Http/              # Controladores, Middlewares, Requests
│   ├── Models/            # Modelos Eloquent (Vehicle, Booking, Customer, etc.)
│   └── Services/          # Lógica de negocio (cálculo de precios, etc.)
├── config/                # Archivos de configuración
├── database/
│   ├── migrations/        # Migraciones de base de datos
│   └── seeders/           # Seeders para datos iniciales
├── public/                # Document root (assets, index.php)
├── resources/             # Vistas Blade y componentes
├── routes/                # Definición de rutas
└── tests/                 # Tests unitarios y funcionales
```

---

## 🗄️ Modelo de datos

### Entidades principales

| Entidad | Descripción |
|---|---|
| **Vehicle** | Vehículos con categoría, ubicación y estado |
| **VehicleCategory** | Categorías con precios y descuentos |
| **PricePeriod** | Períodos de precio (temporada alta/baja) |
| **CategoryVolumeDiscount** | Descuentos por volumen (días) |
| **Booking** | Reservas con fechas, estado y precios |
| **Customer** | Clientes con datos de contacto |
| **Payment** | Pagos asociados a reservas |
| **Invoice** | Facturas generadas desde reservas |
| **Review** | Reseñas y valoraciones |
| **Location** | Ubicaciones de recogida/devolución |

### Cálculo de precios

El sistema calcula automáticamente el precio de una reserva considerando:
1. La categoría del vehículo seleccionado
2. El período de precio que cubre las fechas de la reserva
3. El descuento por volumen (número de días)
4. Servicios adicionales solicitados

---

## 🌐 Licencia

Este proyecto se distribuye bajo la **[Licencia GNU AGPL v3](https://www.gnu.org/licenses/agpl-3.0)**.

### Tus derechos

- ✅ **Usar** el software de forma gratuita para cualquier propósito
- ✅ **Estudiar** cómo funciona (el código fuente está disponible)
- ✅ **Modificar** el código para adaptarlo a tus necesidades
- ✅ **Compartir** copias del software original o modificado
- ✅ **Distribuir** versiones modificadas

### Tus responsabilidades

- 📋 Si modificas el software y lo pones a disposición de otros (incluso como servicio web), debes poner el código fuente de tus cambios a disposición bajo la misma licencia AGPL v3.
- 📋 Debes incluir un aviso con la licencia y el copyright original.
- 📋 El software se proporciona "tal cual", sin garantía de ningún tipo.

Texto completo de la licencia: [GNU AGPL v3](https://www.gnu.org/licenses/agpl-3.0.html)

---

## 🤝 Sobre Sientia Open Source Lab

Somos un laboratorio de desarrollo de software libre, de código abierto y totalmente gratuito. Creemos que el conocimiento y las herramientas deben ser accesibles para todos.

### Nuestra filosofía

| | |
|---|---|
| 🔓 **Open Source Total** | Todo nuestro código es público y libre. Puedes estudiar cómo funciona, modificarlo, adaptarlo a tus necesidades y contribuir a mejorarlo. Sin letra pequeña. |
| 🎁 **Siempre Gratuito** | Nuestras aplicaciones son y seguirán siendo gratis. No hay planes de pago, ni funciones premium ocultas. Lo que ves es lo que hay, para todos por igual. |
| ❤ **Comunidad y Apoyo** | Nos financiamos gracias a personas como tú que valoran el software libre. Tu apoyo en Patreon o Buy Me a Coffee nos ayuda a seguir adelante. |

### Contacto

- 📧 Email: [hola@sientia.com](mailto:hola@sientia.com)
- 🌐 Web: [https://sientia.com](https://sientia.com)
- 👤 El Autor: [https://cv.sientia.com](https://cv.sientia.com)
- 🐙 GitHub: [https://github.com/pbenav](https://github.com/pbenav)
- 🦊 GitLab: [https://gitlab.com/pbenav](https://gitlab.com/pbenav)

### Apoyar el proyecto

El software libre no es gratis de mantener. Si usas nuestras herramientas y quieres que sigan creciendo:

- ❤ [Apoyar en Patreon](https://www.patreon.com/cw/sientia)
- ☕ [Buy Me a Coffee](https://buymeacoffee.com/sientia)

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com/) - El framework PHP para artesanos
- [FilamentPHP](https://filamentphp.com/) - Panel de administración para Laravel
- [TailwindCSS](https://tailwindcss.com/) - Framework CSS utility-first
- [MySQL](https://www.mysql.com/) - Sistema de gestión de bases de datos relacional

---

© 2026 [Sientia Open Source Lab](https://sientia.com) | Licencia [AGPL v3](https://www.gnu.org/licenses/agpl-3.0)
