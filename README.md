


# Project Setup Guide



## Deployment Link

You can access the deployed application at the following link:


<!-- Task Management System - Live -->
<!--[Task Management System - Live](https://your-app-url.com)-->

## Overview
A simple yet powerful task management system to create, update, view, and manage tasks. It allows users to manage tasks with different priorities and deadlines.


## Prerequisites

Before you begin, make sure you have the following installed:


- **PHP** and **Composer**
- **MySQL** 

---

## Step-by-Step Setup

### 1. clone the **backend** repository


```bash
git clone https://github.com/Richswag009/gtext-task.git
cd gtext_task
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

Once everything is set up, you can test the application by visiting the url

```bash
php artisan serve
```
By default, the application will be accessible at http://localhost:8000/api.

---

# API Documentation for Task Management System

## Overview

This API provides endpoints to interact with the Task Management System. It includes operations for registering users,login, managing tasks, including CRUD (Create, Read, Update, Delete) operations, filtering, and sorting tasks based on priority, due date, etc.

### Base URL

**http://localhost:8000/api**



### Register a user

**POST** `/api/auth/register`

#### Request

```json
  "name": "john doe",
  "email": "user@example.com",
  "password": "yourpassword"
```
---

### Response

```json

{
    "status": true,
    "message": "user register successfully",
    "data": {
        "user": {
            "name": "john doe",
            "email": "email1@gmail.com",
            "updated_at": "2025-01-08T18:44:35.000000Z",
            "created_at": "2025-01-08T18:44:35.000000Z",
            "id": 2
        },
        "token": "jwt token"
    }
}
```

---
## Authentication

### JWT Token Authentication

All endpoints require authentication via JWT tokens. To authenticate, include the JWT token in the `Authorization` header of your requests:

## Authorization: Bearer {JWT_TOKEN}

### Obtaining the JWT Token

To obtain a JWT token, send a `POST` request to the `auth/login` endpoint with your login credentials (email and password).

**POST** `/api/auth/login`

#### Request

```json
  "email": "user@example.com",
  "password": "yourpassword"
```
---

### Response

```json

 {
    "status": true,
    "message": "login successful",
    "data": {
        "user": {
            "id": 2,
            "name": "john doe",
            "email": "email1@gmail.com",
            "email_verified_at": null,
            "created_at": "2025-01-08T18:44:35.000000Z",
            "updated_at": "2025-01-08T18:44:35.000000Z"
        },
        "authorization": {
            "token": "jwt token",
            "type": "bearer",
            "expires_in": 3600
        }
    }
}
```


---

Once you have the JWT token, include it in the Authorization header for all further requests.

## Endpoints
### 1. List All Tasks
**GET /api/tasks** 

Fetches a list of all tasks for the authenticated user.

Query Parameters
- priority: (optional) Filter tasks by priority (e.g., high, medium, low).
- start_date: (optional) Filter tasks by start date (e.g., 2025-01-01).
- end_date: (optional) Filter tasks by end date (e.g., 2025-01-10).
- sort_by: (optional) Sort tasks by field (e.g., due_date).
- sort_order: (optional) Sort order, can be either asc or desc (default: asc).

### Example Request

```sql
GET /api/tasks?priority=high&start_date=2025-01-01&end_date=2025-01-10&sort_by=due_date&sort_order=asc
```
---
### Example Response
```json
[
  {
    "id": 1,
    "title": "Finish project report",
    "description": "Complete the final report for the project",
    "priority": "high",
    "due_date": "2025-01-10",
    "user_id": 1
  },
  {
    "id": 2,
    "title": "Submit timesheet",
    "description": "Submit your weekly timesheet",
    "priority": "medium",
    "due_date": "2025-01-08",
    "user_id": 1
  }
]
```
---

### 2. Get Task Details
**GET /api/tasks/{task}**

Fetches details of a specific task by its ID.

URL Parameters
- task: The ID of the task.

### Example Request

```sql
GET /api/tasks/1
```

### Example Response

```json
[
  {
    "id": 1,
    "title": "Finish project report",
    "description": "Complete the final report for the project",
    "priority": "high",
    "due_date": "2025-01-10",
    "user_id": 1
  },
]
```
---


### 3. Create a New Task
**POST /api/tasks**

Create a new task.

### Example Request
```json
{
  "title": "Buy groceries",
  "description": "Buy groceries for the week",
  "priority": "low",
  "due_date": "2025-01-15"
}
```

### Example Response

```json
{
  "id": 3,
  "title": "Buy groceries",
  "description": "Buy groceries for the week",
  "priority": "low",
  "due_date": "2025-01-15",
  "user_id": 1
}
```
---
### 4. Update a Task

```sql
PUT /api/tasks/{task}**
```

Update an existing task by its ID.

URL Parameters
- task: The ID of the task.
### Example  Request
```json
{

  "title": "Buy groceries and cook dinner",
  "description": "Buy groceries and prepare dinner for the week",
  "priority": "medium",
  "due_date": "2025-01-16"
}
```
### Example Response
```json
{
  "id": 3,
  "title": "Buy groceries and cook dinner",
  "description": "Buy groceries and prepare dinner for the week",
  "priority": "medium",
  "due_date": "2025-01-16",
  "user_id": 1
}
```
### 5. Delete a Task
**DELETE /api/tasks/{task}**

Delete a specific task by its ID.

URL Parameters
- task: The ID of the task.
### Example  Request

```bash
DELETE /api/tasks/3
```


## Example Response
```json
{
  "message": "Task deleted successfully"
}
```

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

---

<!-- ## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details. -->

---

