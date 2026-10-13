# Deploying Laravel to Hostinger (Laravel 12, PHP >= 8.2)

This guide walks you through deploying this project to Hostinger shared hosting using hPanel + SSH.

## Requirements
- Hostinger plan with SSH access enabled
- PHP 8.2 or 8.3 selected in hPanel
- Extensions: pdo_mysql, mbstring, tokenizer, xml, ctype, fileinfo, openssl, curl (default on Hostinger)
- Composer available over SSH (Hostinger provides Composer v2)

## 1) Prepare Locally
- Build production assets (Hostinger shared typically lacks Node):
```
npm ci
npm run build
```
- Commit or upload the generated `public/build` folder.

## 2) Upload Project
You can use one of:
- Git deploy (recommended): push to a repo and use Hostinger Git deployment.
- SFTP/FTP: upload entire project to a folder (e.g., `~/laravel/tourmatesdb`).

## 3) Point Document Root to `public/`
In hPanel → Websites → Manage → Advanced → Change domain document root (or Subdomain document root)
- Set it to `<your-project>/public`

Alternatively, if you cannot change document root, move the contents of `public/` into `public_html/` and edit `index.php` paths. Changing document root is safer.

## 4) Create Production .env
Create a production `.env` in your project root on the server based on `deploy/hostinger.env.example`:
- Set your Hostinger MySQL credentials
- Set `APP_ENV=production` and `APP_DEBUG=false`
- Set `APP_URL` to your domain (e.g., `https://example.com`)
- Configure mail (SMTP) if needed

## 5) SSH In and Install Dependencies
In hPanel → Advanced → SSH access → Open a Web SSH session, then run:
```
cd ~/path/to/your/project
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6) File Permissions
Ensure writable directories:
```
chmod -R ug+rw storage bootstrap/cache
```
On shared hosting this is usually fine by default.

## 7) Queues and Scheduler (Optional)
If you use queues in production, either:
- Switch to sync in `.env`:
```
QUEUE_CONNECTION=sync
```
- Or run a worker with cron (Hostinger → Cron Jobs):
```
* * * * * /usr/bin/php /home/<user>/path/to/project/artisan schedule:run >> /dev/null 2>&1
* * * * * /usr/bin/php /home/<user>/path/to/project/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

## 8) Verify
- Visit your domain. The welcome page should load.
- Check `storage/logs/laravel.log` for any errors.

## Troubleshooting
- 500 error: set `APP_DEBUG=false` in production, but check logs for the underlying issue. Ensure `vendor/` exists and Composer ran.
- CSS/JS missing: ensure `public/build` was deployed (from `npm run build`).
- DB connection error: confirm host/user/pass/DB name in `.env` (Hostinger often uses `localhost`), and that the DB exists in hPanel → Databases.
- Symlink issues: `php artisan storage:link` should work; if not, copy `storage/app/public` contents into `public/storage` as a fallback.
- Wrong PHP version: set PHP 8.2+ in hPanel → PHP configuration.
