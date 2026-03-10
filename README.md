# Job Board API

A RESTful Job Board API built with Laravel.

This API allows companies to post jobs and applicants to apply for jobs.

---

## Features

- User Registration and Login
- Authentication using Laravel Sanctum
- Companies can create and manage jobs
- Applicants can apply to jobs
- Job search and filtering
- Pagination
- Dashboard statistics

---

## Installation

Clone the repository:

```
git clone https://github.com/yourusername/job-board-api.git
```

Install dependencies:

```
composer install
```

Copy environment file:

```
cp .env.example .env
```

Generate application key:

```
php artisan key:generate
```

Run migrations:

```
php artisan migrate
```

Start server:

```
php artisan serve
```

---

## API Endpoints

### Register

POST /api/register

Request:

```json
{
"name":"Joel",
"email":"joel@email.com",
"password":"123456",
"role":"company"
}
```

Response:
```json
{
 "user": {
  "id":1,
  "name":"Joel",
  "email":"joel@email.com",
  "role": "company"
 },
 "token":"1|ksjdklfjsdlkf"
}

---

### Login

POST /api/login

Request:

```json
{
"email":"joel@email.com",
"password":"123456"
}
```

---

### Get Jobs

GET /api/jobs

Optional filters:

```
/api/jobs?search=developer
/api/jobs?location=remote
/api/jobs?page=2
```

---

### Create Job

POST /api/jobs

Headers:

```
Authorization: Bearer TOKEN
```

---

### Apply for Job

POST /api/jobs/{id}/apply

---

### Dashboard

GET /api/dashboard

Returns statistics depending on user role.

---

## Tech Stack

- Laravel
- MySQL
- Laravel Sanctum
- REST API
