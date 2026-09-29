# INULIN

Laravel 12 application for a real Windows and Linux reinstallation service.

## Run from D:

```powershell
cd D:\project\INULIN-WEB
php artisan migrate:fresh --seed
php artisan serve
```

Development uses the SQLite file at `database/database.sqlite`, stored on D:. The same migrations support MySQL by setting `DB_CONNECTION=mysql`, host, port, database, username, and password in `.env`.

Seeded admin account: `admin@inulin.test` / `password`. Change it before deployment.

## Design and quality

The visual direction follows the supplied Stitch references: charcoal surfaces, cyan action color, dense but readable booking panels, and a task-first admin navigation. Anti-slop rules are applied during implementation. Unverified metrics, ratings, testimonials, and decorative controls are omitted. The logo supplied by the user is stored at `public/brand/inulin.png`.

The public flow is `/` → `/services/instal-ulang` → booking form → persisted booking summary → URL-encoded WhatsApp deep link. Admin starts at `/admin/login`.
