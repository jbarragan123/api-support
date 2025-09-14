# App Support

Sistema de soporte técnico con **Laravel 12 (backend)** y **Vue 3 + Vite + Bootstrap (frontend)**.  
Incluye autenticación JWT, roles, notificaciones por logs, dashboard de administración y endpoint de sugerencia de respuestas con IA.

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

Agrega tu token de OpenAI adjunto en el correo en `.env`:

```env
OPENAI_API_KEY=tu_token_aqui
```

#### ⚠️ Configurar certificado SSL en Wamp (Windows)

> Esto evita errores `cURL error 60` al usar OpenAI. Si no se configura, el endpoint seguirá funcionando pero devolverá **respuestas predefinidas de reglas básicas** en lugar de la IA.

1. Descarga el archivo de certificados raíz: [cacert.pem](https://curl.se/ca/cacert.pem)  
2. Guárdalo en:  
   `C:\wamp64in\php\php8.2.x\extras\ssl\cacert.pem` (ajusta la versión de PHP)  
3. Edita `php.ini` de Wamp (PHP → php.ini) y agrega o reemplaza:  
   ```ini
   curl.cainfo = "C:\wamp64in\php\php8.2.x\extras\ssl\cacert.pem"
   openssl.cafile = "C:\wamp64in\php\php8.2.x\extras\ssl\cacert.pem"
   ```
4. Reinicia Wamp (`Restart All Services`)  


### Frontend (Vue 3 con Vite)

```bash
cd ../frontend
npm install
```

---

## ▶️ Ejecución

### Servidores separados 2 consolas

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

Entrar a http://localhost:5173/ una vez se esté ejecutando ambas instancias

---

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
- `POST /api/solicitudes/sugerencia` → genera sugerencia automática con IA (si falla la API, devuelve respuesta de reglas básicas)

**Nota:** En la carpeta del proyecto hay una colección Postman para importar y probar todos los endpoints.

---

## Estado del proyecto - Prueba técnica

### ✅ Implementado según checklist minimo y extras valorados

#### Autenticación y autorización
- Login con JWT (`POST /api/auth/login`).
- Roles implementados: `cliente`, `soporte`, `administrador`.
- Respuesta del login devuelve `token` y `user` con `role` (nombre del rol, no id).

#### Endpoints obligatorios
- **POST /auth/login:** login y obtención de token JWT.  
- **POST /solicitudes:** creación de solicitud (solo clientes).  
- **GET /solicitudes:** listado filtrado según rol:
  - Admin: todas las solicitudes.
  - Soporte: solo las asignadas.
  - Cliente: solo las propias.  
  Relación con `user` y `soporte` cargadas.
- **PUT /solicitudes/{id}:** actualización de estado y respuesta (soporte/admin).  
- **GET /reportes/solicitudes:** resumen por estado (`abierta`, `en proceso`, `cerrada`).

#### Modelo de datos
- `User` → relación `belongsTo` con `Role`.  
- `Role` → relación `hasMany` con `User`.  
- `Solicitud` → relación con `User` y `Soporte`.  
- Historial de cambios preparado (aunque no se implementaron endpoints extras).

#### Extras realizados

- Documentación.
- Validaciones y sanitización de inputs.
- Manejo de errores y respuestas consistentes.
- Seguridad: rate limiting, protección contra SQL Injection, CORS.
- Notificaciones por correo al crear o actualizar solicitudes.
- Endpoint que use IA o reglas básicas para generar una respuesta
automática sugerida.
- Estructura limpia (ej. repositorios, servicios, controladores en Laravel/Node).

### ⚠️ Pendiente / Mejoras según checklist

- Documentación en swagger

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

En caso de que tengan alguna duda o problema no duden en contactarme a **orionmaster8@gmail.com** o app 3125291007
