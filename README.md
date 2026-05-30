# News Management API

A RESTful API built with Laravel. This project utilizes Laravel Passport for secure API authentication (via Personal Access Tokens) and Redis for background queue processing.

## Prerequisites

Before setting up the project, ensure your local environment meets the following requirements:
* **PHP** (v8.1 or higher)
* **Composer**
* **MySQL** or **PostgreSQL**
* **Redis Server**
* **PHP Redis Extension (`phpredis`)** installed and enabled

---

## Installation & Setup

Follow these steps sequentially to set up the project on your local machine.

### 1. Clone the Repository
Clone the project and navigate into the project directory:
```bash
git clone https://github.com/aninkinaa/news-management-api.git
cd news-management-api
```

### 2. Install Dependencies
Install all required PHP packages using Composer:
```bash
composer install
```

### 3. Environment Configuration
Create a copy of the environment file:
```bash
cp .env.example .env
```
Open the `.env` file and configure your database and Redis settings. Ensure the following specific keys are set correctly:
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news-management-api
DB_USERNAME=root
DB_PASSWORD=

# Queue and Redis Configuration
QUEUE_CONNECTION=redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Generate Application Key
Generate the unique application encryption key required to secure sessions and encrypted data:
```bash
php artisan key:generate
```

### 5. Database Migration
Run the migrations to create all necessary tables, including Laravel's default tables and Passport's `oauth_*` tables:
```bash
php artisan migrate
```

### 6. Laravel Passport Setup
Generate the encryption keys (`oauth-private.key` and `oauth-public.key`) required by Passport:
```bash
php artisan passport:keys
```
Create a Personal Access Client. This enables token generation directly from the application code for internal API consumption:
```bash
php artisan passport:client --personal
```

### 7. Clear Configuration Cache
Clear the application cache to ensure Laravel reads your newly updated `.env` settings:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Running the Application

To fully run the application and process background tasks (like `CreateCommentJob`), you need to open two separate terminal windows.

**Terminal 1: Start the Local API Server**
```bash
php artisan serve
```
*The API will be accessible at `http://127.0.0.1:8000`.*

**Terminal 2: Start the Queue Worker**
In a new terminal window, run the Redis queue worker to listen for and execute background jobs:
```bash
php artisan queue:work
```