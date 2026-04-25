# Serabutin

Backend API for the **Serabutin** project, built with Laravel.

## Requirements

Make sure you have installed:

- PHP >= 8.3
- Composer
- PostgreSQL

## Project Setup

### 1. Clone Repository

```bash
git clone https://github.com/Ablebil/serabutin-be.git
cd serabutin-be
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

Copy the environment file:

```bash
cp .env.example .env
```

Then configure your database in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=serabutin_db
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Server

```bash
php artisan serve
```

Access the API at:

```
http://127.0.0.1:8000
```

## API Structure

All endpoints use the prefix:

```
/api/v1
```

Example:

```
/api/v1/auth/login
```

## Project Structure (Simplified)

```
app/
 └── Http/
     └── Controllers/
         └── Api/
             └── V1/

routes/
 └── api.php
```
