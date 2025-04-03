# Laravel Project Setup Guide

## 🚀 Step-by-Step Guide After Cloning a Laravel Project

### 1️⃣ **Clone the Repository**

```bash
    git clone <repository-url>
    cd <project-folder>
```

### 2️⃣ **Install Dependencies**

```bash
    composer install
```

### 3️⃣ **Copy and Configure Environment File**

```bash
    cp .env.example .env
```

### 4️⃣ **Generate Application Key**

```bash
    php artisan key:generate
```

### 5️⃣ **Set Up Database**

- Open `.env` and configure **DB settings** (MySQL, SQLite, PostgreSQL, etc.)

Example for MySQL:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

## 🙌 **Don't forget to open xampp**

### 6️⃣ **Run Migrations & Seed Database**

```bash
  php artisan migrate --seed
```

_If you need to reset everything:_

```bash
  php artisan migrate:fresh --seed
```

### 7️⃣ **Set File Permissions (if needed)**

```bash
  chmod -R 775 storage bootstrap/cache
```

### 7️⃣ **Install Frontend Dependencies (if applicable)**

```bash
  npm install
```

```bash
  npm run dev
```

### 8️⃣ **Run the Development Server**

```bash
  php artisan serve
```

### 🎉 **You're Ready to Go!**

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser! 🚀
