# Tasks Management

## Requirements

- PHP 8.2 or higher
- Composer
- Docker & Docker Compose ([Docker Desktop](https://docs.docker.com/desktop/) or [OrbStack](https://orbstack.dev/) if you use macOS)
- Git
- [Postman](https://www.postman.com/) (optional)

### 1. Clone the project and install dependencies

```shell
git clone https://github.com/jualexandre/tasks-management
cd tasks-management

composer install

cp .env.example .env # Don't forget to fill in the database credentials as mentioned in the docker-compose.yaml
```

### 2. Launch the database

```shell
# Start Docker services (database)
docker-composer up -d

# Run migrations
php artisan migrate:install
php artisan migrate

# Populate the database with test data
php artisan db:seed
```

### 3. Launch the development server

```shell
php artisan serve
```

## Instructions

### 🎯 Context

You have a Laravel API with a `Task` model with the following fields: 
`id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`, `updated_at`.


### 📝 To do

#### Exercise 1: Individual consultation route

Implement an API route to retrieve a specific task by its ID.

**Example of expected URL:**
```text
GET /api/tasks/5
```

**Expected response (success):**
```json
{
    "success": true,
    "message": "",
    "data": {
        "id": 5,
        "title": "...",
        "description": "...",
        "status": "...",
        "priority": "...",
        "due_date": "...",
        "created_at": "...",
        "updated_at": "..."
    }
}
```

**Expected response (error):**
```json
{
    "success": false,
    "message": "..."
}
```

**Evaluation criteria:**
- ✅ Use of Laravel best practices (Route Model Binding)
- ✅ Handling of resource non-existent cases (404 HTTP status)
- ✅ Return to structured JSON format

---

#### Exercise 2: Route with multiple filters and sorting

Implement an API route that returns tasks based on specific criteria.

**Specifications:**
- Status: `pending` only
- Priority: `low` only
- Sort: By creation date (most recent first)

**Example of expected URL:**
```text
GET /api/tasks/pending-low-priority
```

**Expected response (success):**
```json
{
    "success": true,
    "message": "",
    "data": [
        {
            "id": "...",
            "title": "...",
            "description": "...",
            "status": "pending",
            "priority": "low",
            "due_date": "...",
            "created_at": "...",
            "updated_at": "..."
        },
        {
            "id": "...",
            "title": "...",
            "description": "...",
            "status": "pending",
            "priority": "low",
            "due_date": "...",
            "created_at": "...",
            "updated_at": "..."
        },
        ...
    ]
}
```

**Evaluation criteria:**
- ✅ Using Query Scopes (recommended) OR classic Eloquent queries
- ✅ Sort by created_at descending
- ✅ Proper JSON response structure
- ✅ Best Practices for Naming Routes and Functions

---

#### Exercise 3: Error debugging

A developer attempted to implement a task creation route but made mistakes...

Your mission: **Identify and fix the error** by analyzing the error messages.

**Request:**
```shell
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{"title": "Task", "description": "...", "status": "pending", "priority": "medium", "due_date": "2025-09-20"}' \
  http://localhost:8000/api/tasks/
```

**Expected response (success):**
```json
{
    "success": true,
    "message": "Task created successfully",
    "data": {
        "title": "Task",
        "description": "...",
        "status": "pending",
        "priority": "medium",
        "due_date": "2025-09-20T00:00:00.000000Z",
        "updated_at": "2025-09-03T09:38:59.000000Z",
        "created_at": "2025-09-03T09:38:59.000000Z",
        "id": 514
    }
}
```

**Evaluation criteria:**
- ✅ Understanding HTTP Methods
- ✅ Reading HTTP error messages
- ✅ Knowledge of Laravel routing

---

### 🧪 How to test

```shell
# Start the server
php artisan serve

# Test the creation route 
curl -X POST -H "Content-Type: application/json" -d '{"title": "Task", "description": "...", "status": "pending", "priority": "medium", "due_date": "2025-09-20"}' http://localhost:8000/api/tasks/

# Test the individual route
curl -X GET -H "Content-Type: application/json" http://localhost:8000/api/tasks/5

# Test the route with filters
curl -X GET -H "Content-Type: application/json" http://localhost:8000/api/tasks/pending-low-priority
```
