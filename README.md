


# Project Setup Guide
# Task Management System
## Overview
A simple yet powerful task management system to create, update, view, and manage tasks. It allows users to manage tasks with different priorities and deadlines, assign tasks to team members, and track progress. This API provides endpoints for managing 


## Prerequisites

Before you begin, make sure you have the following installed:


- **PHP** and **Composer**
- **MySQL** 

---

## Step-by-Step Setup

### 1. clone the **backend** repository


```bash
git clone https://github.com/hardeex/bitz-backend
cd bitz-backend
```

### 3. Install Backend Dependencies

Install the required PHP dependencies using **Composer**:

```bash
composer install
```

This will download all necessary libraries for the backend.

### 3. Set Up the Database

1. **Create the Database**: Create a MySQL database 
2. **Configure `.env`**: Copy the `.env.example` file to `.env` and configure the database and other environment variables accordingly.

For MySQL, set the database connection:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```


### 4. Run Migrations

Run the database migrations to set up the necessary tables:

```bash
php artisan migrate
```

---

### 5. Generate Application Key

Run the following Artisan command to generate a unique application key. This key is used to secure user sessions and other encrypted data:

```bash
php artisan key:generate
```
This will update the .env file with a APP_KEY value.

---

### 6. Generate JWT Secret Key
The JWT secret is used to sign your tokens. Generate it using the following Artisan command:

```bash
php artisan jwt:secret
```
This command will update your .env file with a JWT_SECRET value. If you already have a JWT secret in the .env file, it will be replaced with a new one.

---

### 9. Start the Application

Once everything is set up, you can test the application by visiting the frontend in your browser. The frontend should now be able to communicate with the backend API and everything should work as expected.

```bash
php artisan serve
```
By default, the application will be accessible at http://localhost:8000.

---


<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

---

<!-- ## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details. -->

---

