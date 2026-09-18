# Red App — API de Contactos con Imágenes (Laravel)

API REST en **Laravel 12** para gestionar una **red de contactos** con fotos de perfil:
CRUD de usuarios y subida de imágenes, con relación polimórfica `Image`.

## Stack

- **Backend:** PHP 8.2 + Laravel 12, Eloquent ORM.
- **Base de datos:** PostgreSQL (Laravel Sail / Docker `compose.yaml`).
- **Frontend:** Vite + Tailwind 4 (starter).
- **Testing:** Pest + factories.

## Endpoints

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET/POST | `/api/users` | Listar / crear usuarios |
| PUT/DELETE | `/api/users/{user}` | Actualizar / eliminar usuario |
| POST | `/api/users/{user}/images` | Subir imagen de perfil (multipart) |
| DELETE | `/api/images/{image}` | Eliminar imagen |
| GET | `/api/seed` | Ejecutar seed de datos |
| GET | `/api/reset-seed` | Reiniciar seed |

Las imágenes se almacenan en `storage/app/public/images`.

## Cómo ejecutar

```sh
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
npm install && npm run build

# Dev integrado
composer run dev

# o con Docker / Sail
docker compose up
```

## Soporte

Despliegue listo para Render (`render.yaml`). La API actual no exige autenticación
(Sanctum instalado para futuras versiones).