# App Support

Sistema de soporte técnico con **Laravel 12 (backend)** y **Vue 3 + Vite + Bootstrap (frontend)**.  
Incluye autenticación JWT, roles, notificaciones por logs y dashboard de administración.

---

## 📦 Requisitos

- PHP ^8.2 y Composer
- Node.js ^18 y NPM
- MySQL
- Opcional: Postman o Insomnia para probar la API

---

## ⚙️ Instalación

Clonar el repositorio:

```bash
git clone https://github.com/jbarragan123/app-support.git
cd app-support
```

### Backend (Laravel 12)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Configura la base de datos en `.env` y luego ejecuta:
(crear la bd localmente y agregarla en la variable del .env DB_DATABASE)
```bash
php artisan migrate --seed
```

Dependencias de JWT ya incluidas (`tymon/jwt-auth`).  
Generar secret key:

```bash
php artisan jwt:secret
```

### Frontend (Vue 3 con Vite)

```bash
cd ../frontend
npm install
```

---

## ▶️ Ejecución

### Opción 1: Servidores separados

- Backend:  
  ```bash
  cd backend
  php artisan serve
  ```

- Frontend:  
  ```bash
  cd frontend
  npm run dev
  ```

## 👥 Usuarios de prueba (seeders)

Se crean automáticamente con `php artisan migrate --seed`.

- Cliente Demo → **cliente@demo.com / password123**
- Cliente2 Demo2 → **cliente2@demo.com / password123**
- Soporte Demo → **soporte@demo.com / password123**
- Soporte2 Demo2 → **soporte2@demo.com / password123**
- Admin Demo → **admin@demo.com / password123**
- Admin2 Demo2 → **admin2@demo.com / password123**

---

## 🔐 Roles y permisos

- **Admin**: puede ver todas las solicitudes, dashboard con reporte resumido.  
- **Soporte**: puede ver únicamente las solicitudes asignadas.  
- **Cliente**: solo puede ver y gestionar sus propias solicitudes.

---

## ✉️ Notificaciones y correos

Las notificaciones están configuradas en **logs**.  
Para ver los “correos enviados”:

```bash
cd backend
php artisan queue:listen
tail -f storage/logs/laravel.log
```

---

## 📌 Endpoints principales

- `POST /api/login` → autenticación y token JWT  
- `GET /api/solicitudes` → listar solicitudes (según rol)  
- `POST /api/solicitudes` → crear solicitud  
- `PUT /api/solicitudes/{id}` → actualizar solicitud  
- `GET /api/reporte` → resumen por estado (solo Admin)

Nota: En la carpeta del proyecto hay una colección postman para importar y probar
---

## 🌟 Extras implementados

- Dashboard con reporte para Admin  
- Notificaciones por correo simuladas en logs  
- Validación asignación de tareas para usuario con menos carga de solicitudes

---

## 📂 Estructura del proyecto

```
app-support/
 ├── backend/   # Laravel 12 + JWT + MySQL
 └── frontend/  # Vue 3 + Vite + Bootstrap
```

---

## 🚀 Entrega

Repositorio: **app-support**  
Contiene **backend** y **frontend** integrados, listos para correr localmente.

