# Task Management System
Laravel-based Simple Task management System

---
## Features

- User registration and authentication
- Create tasks
- Task status tracking: Pending → In Progress → Done
- Task state: published or drafted
- Paginations, Filter, Sorting and Search Task
- Soft deletion of task (Trash - will be removed automatically after 30 days)

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11, PHP 8.1+
- **Database**: MySQL 
- **Frontend**: Blade / Jquery*
---

## ⚙️ Installation

### 1. Clone the repository

```
git clone https://github.com/misonnnnn/taskmanagement.git
cd taskmanagement
composer install
cp .env.example .env
```

### 2. Update .env database credentials:
DB_DATABASE=taskmanagement
DB_USERNAME=root
DB_PASSWORD=

### 3. Run migrations
php artisan migrate

php artisan serve

then go visit 
http://127.0.0.1:8000/


## other commands
php artisan app:remove-trash-data
- This command scans for tasks in the trash and permanently deletes any that have been there for more than 30 days.
