# Laravel Sanctum API (User & Post Management)

This project is a RESTful API built using Laravel with Sanctum authentication and Policy-based authorization. It includes user management, profile handling, and post CRUD operations with secure access control.

---

##  Features

- User Registration & Login
- Laravel Sanctum Authentication (Token-based)
- Policy-based Authorization (Access Control)
- User Profile Get & Update
- Create, Read, Update, Delete (CRUD) for Posts
- Protected Routes using Middleware
- API Testing using Postman

---

##  Tech Stack

- Laravel
- Laravel Sanctum
- MySQL
- PHP
- Postman

---

##  Project Modules

###  Authentication
- Register User
- Login User
- Logout User

###  User Profile
- Get Profile
- Update Profile

###  Posts
- Create Post
- Get Posts
- Update Post
- Delete Post

---

##  Authorization (Policies)

This project uses **Laravel Policies** to control access to posts and user-related actions.

### Policy Features:
- Users can only update/delete their own posts
- Access control enforced at model level using Policies
- Prevents unauthorized modifications
---

##  API Endpoints

### Auth Routes
- POST `/api/signUp`
- POST `/api/login`
- POST `/api/user/logout`

### Profile Routes
- GET `/api/user/profile`
- POST `/api/user/profile/update`

### Post Routes
- GET `/api/user/posts`
- POST `/api/user/posts`
- POST `/api/user/posts/{id}` (Update)
- DELETE `/api/user/posts/{id}`

---

## 🧪 API Testing

All APIs are tested using **Postman**.

Postman collection is available in:

Environment variables used:
- `api_url`
- `token`
- `email`
- `password`

---
##  Installation

-git clone <repo-url>
-cd project-folder
-composer install
-cp .env.example .env
-php artisan key:generate
-php artisan migrate
-php artisan serve

 Notes
-Ensure .env file is properly configured
-Sanctum should be installed and configured
-Policies are registered in AuthServiceProvider
-Use Postman for API testing

 Author
--Developed by Amisha Gupta
