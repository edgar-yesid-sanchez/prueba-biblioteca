# Proyecto Biblioteca (Angular + Laravel + JWT)

Este proyecto es una aplicación de gestión de libros y préstamos desarrollada con **Laravel (backend)** y **Angular (frontend)**, utilizando **JWT** para la autenticación.

---

## 🔧 Tecnologías usadas

* Laravel 12
* Angular 19
* Tailwind CSS
* JWT (tymon/jwt-auth)
* MySQL
* Swagger

## Requisitos previos

### Backend (Laravel)

* PHP 8.1 o superior
* Composer
* MySQL o MariaDB

### Frontend (Angular)

* Node.js 22+
* Angular CLI

---

## Instalación del Backend (Laravel)

1. Clona el repositorio:

```bash
cd biblioteca-api
composer install
```

2. Crea tu archivo `.env`:

```bash
cp .env.example .env
```

> Nota: el archivo `.env.example` ya contiene un `JWT_SECRET` generado para evitar tener que correr `php artisan jwt:secret`

3. Configura la conexión a la base de datos en `.env`:

```env
DB_DATABASE=biblioteca
DB_USERNAME=root
DB_PASSWORD=tu_password
```

4. Genera la clave de la app:

```bash
php artisan key:generate
```

5. Ejecuta las migraciones y seeders:

```bash
php artisan migrate:fresh --seed
```

6. Inicia el servidor de desarrollo:

```bash
php artisan serve
```

El backend estará disponible en: [http://localhost:8000](http://localhost:8000)

---

## Instalación del Frontend (Angular)

1. Ve a la carpeta del frontend:

```bash
cd biblioteca-front
npm install
```

2. Inicia el servidor de desarrollo:

```bash
ng serve
```

La aplicación estará disponible en: [http://localhost:4200](http://localhost:4200)

---

## Usuarios Iniciales del seeder

Estos usuarios se generan automáticamente con el seeder:

### Admin

* **Email**: [admin@admin.com](mailto:admin@admin.com)
* **Contraseña**: 123456

### Usuarios normales

* **[juan@email.com](mailto:juan@email.com)** / 123456
* **[pedro@email.com](mailto:pedro@email.com)** / 123456

---

## Seguridad

* Autenticación basada en JWT
* Las rutas protegidas requieren `Authorization: Bearer {token}`
* Middleware personalizado para validar roles

---

## Pruebas

Este proyecto incluye pruebas unitarias básicas para el backend (Laravel) y frontend (Angular).

### 📂 Backend (Laravel)

Se usan pruebas tipo `Feature` para validar la lógica de creación y control de préstamos, así como el acceso a métricas:

```bash
php artisan test
```

## Documtacion

La api cunta con documetacion realizada en Swagger accecible en la ruta [localhost:8000/api/documentation](localhost:8000/api/documentation)


## Funcionamiento Pagina

* Pagina de inicio donde se pueden ver todos los libros y su estado,
* Selecciona un libro para abrir sus descrippcion y poder realizar el prestamo si esta disponible
* Prestamo solo se podra realizar por un usurio registrado,
* Botones de regitro e ingreso en el header
* Al aber un usuario en secion se muestra oppciones de paginas 
* **Prestamos** Todos
* **Gestionar libros** Admin
* **Metrricas** Admin

*  **Pagina prestamos**
* Se muestran solo los prestamos del usurio en secion
* puede renovar prestamos pendientes
* Si es administrador se muestran todos los prestamos
* puede renovar o marcar como entregado

* **Pagina Gestionar lisbros**
* Se muestra un tabla con todos los libros y las funcionalidades CRUD


* **Pagina Metricas**
* Metricas de los prestamos realizados
* Metrica de libros mas solicitados







