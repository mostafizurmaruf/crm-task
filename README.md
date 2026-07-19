# CRM Task

A simple CRM/POS application built with Laravel and MySQL.

## Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm (for frontend assets)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/mostafizurmaruf/crm-task.git
cd crm-task
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create environment file

```bash
cp .env.example .env
```

### 4. Configure database

Update the following values in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_task
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run migrations

```bash
php artisan migrate
```

> If seeders are available:

```bash
php artisan db:seed
```

or

```bash
php artisan migrate --seed
```

### 8. Start the application

```bash
php artisan serve
```

Open:

```
http://127.0.0.1:8000
```

---

## Environment Configuration

The application uses a standard Laravel `.env` configuration.

Example:

```env
APP_NAME=CRM
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_task
DB_USERNAME=root
DB_PASSWORD=
```

---

## Sample Credentials

If database seeders are used:

```
Email: admin@example.com
Password: password
```

---

## Tech Stack

- Laravel
- Blade
- MySQL
- Bootstrap
- JavaScript
