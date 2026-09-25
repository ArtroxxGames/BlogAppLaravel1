# Blog App

Plataforma de blog hecha con **Laravel 12**, **Blade**, **Tailwind CSS** y **Alpine.js**.
Cualquier persona registrada puede escribir artículos en Markdown, comentarlos y valorarlos con estrellas;
los administradores gestionan categorías, moderan comentarios y administran usuarios.

## Funcionalidades

**Blog público**
- Portada con los últimos artículos, paginación y buscador.
- Página de artículo con Markdown renderizado de forma segura, tiempo de lectura, valoración media y artículos relacionados.
- Listado de categorías y página por categoría; las categorías destacadas aparecen en el menú.
- Perfil público de cada autor con biografía, redes sociales y sus artículos.

**Autores** (cualquier usuario registrado)
- Panel con estadísticas: artículos publicados, borradores, comentarios recibidos y valoración media.
- Crear, editar y eliminar sus artículos: borrador, publicación inmediata o **programada** a una fecha futura.
- Imagen de portada opcional; el slug de la URL se genera solo y no cambia al editar.
- Comentar y valorar (1–5 ★) cada artículo una sola vez.
- Perfil editable: foto, profesión, biografía y enlaces a Twitter/X, LinkedIn y GitHub.

**Administradores**
- CRUD de categorías (visibles/ocultas, destacadas, imagen).
- Moderación de comentarios.
- Gestión de usuarios: dar o quitar permisos de administrador.

**Calidad**
- Autorización con *policies* y un *gate* `admin`: nadie puede editar ni borrar contenido ajeno.
- Validación con *Form Requests* y mensajes en español.
- Los archivos subidos se borran automáticamente al reemplazarlos o al eliminar su registro.
- Modo estricto de Eloquent en desarrollo para detectar consultas N+1.
- 74 tests automatizados y CI con GitHub Actions (PHP 8.2, 8.3 y 8.4).

## Requisitos

- PHP 8.2 o superior con las extensiones `pdo_sqlite` (o `pdo_mysql`) y `gd`
- Composer 2
- Node.js 20 o superior

## Instalación

```bash
git clone https://github.com/ArtroxxGames/BlogAppLaravel1.git
cd BlogAppLaravel1

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite   # por defecto se usa SQLite
php artisan migrate --seed       # crea las tablas y carga datos de demostración
php artisan storage:link         # hace públicas las imágenes subidas

composer run dev                 # servidor, cola, logs y Vite a la vez
```

Abre <http://localhost:8000> e inicia sesión con la cuenta de demostración:

| Correo              | Contraseña | Rol           |
|---------------------|------------|---------------|
| `admin@example.com` | `password` | Administrador |

> Cambia esa contraseña, o no ejecutes el *seeder*, si despliegas la aplicación en un servidor real.

### Usar MySQL en lugar de SQLite

Edita el `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

### Correo

Por defecto los correos (bienvenida, recuperación de contraseña) se escriben en `storage/logs/laravel.log`
(`MAIL_MAILER=log`). Configura las variables `MAIL_*` para enviarlos de verdad.
El correo de bienvenida se envía por la cola, así que en producción necesitas un *worker* (`php artisan queue:work`).

## Tests

```bash
php artisan test        # suite completa
vendor/bin/pint         # formatea el código
```

## Estructura

```
app/
├── Http/Controllers/
│   ├── Admin/          # categorías, comentarios y usuarios (solo administradores)
│   ├── Dashboard/      # gestión de artículos del autor
│   └── ...             # blog público, comentarios y perfil
├── Http/Requests/      # validación de formularios
├── Models/             # Article, Category, Comment, User
├── Policies/           # quién puede ver, editar o borrar qué
└── Notifications/      # correo de bienvenida
resources/views/
├── blog/               # páginas públicas
├── dashboard/          # panel del autor
├── admin/              # panel de administración
└── components/         # tarjetas, avatar, estrellas, etc.
lang/es/                # traducciones al español
tests/Feature/          # tests de extremo a extremo
```

## Rutas principales

| Ruta                          | Descripción                         |
|-------------------------------|-------------------------------------|
| `/`                           | Portada y buscador (`?q=`)          |
| `/articulos/{slug}`           | Artículo                            |
| `/categorias`                 | Todas las categorías                |
| `/categorias/{slug}`          | Artículos de una categoría          |
| `/autores/{id}`               | Perfil público de un autor          |
| `/dashboard`                  | Panel del autor                     |
| `/dashboard/articulos`        | Mis artículos                       |
| `/admin/categorias`           | Administración de categorías        |
| `/admin/comentarios`          | Moderación de comentarios           |
| `/admin/usuarios`             | Administración de usuarios          |

## Licencia

[MIT](https://opensource.org/licenses/MIT)
