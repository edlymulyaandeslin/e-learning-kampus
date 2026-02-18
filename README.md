# 🎓 E-Learning Kampus API

API untuk platform e-learning berbasis Laravel 10

---

## 🚀 Teknologi

- **Framework**: Laravel 10
- **Autentikasi**: Laravel Sanctum
- **Database**: MySQL

---

## 🔐 Autentikasi

Semua endpoint (kecuali `login` dan `register`) menggunakan **Bearer Token (Sanctum)**.

### **Register**

```http
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret",
  "password_confirmation": "secret",
  "role": ["lecturer", "student"]
}
RESPONSE
{
    "status" : "success",
    "message" : "User registered successfully"
}
```

### **Login**

```http
POST /api/login
{
  "email": "john@example.com",
  "password": "secret",
}
RESPONSE
{
    "status": "success",
    "message": "User logged in successfully",
    "access_token": "your_token",
    "token_type": "Bearer"
}
```

### **Logout**

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/logout
RESPONSE
{
    "status": "success",
    "message": "User logged out successfully",
}
```

### **Get All Courses**

```http
Authorization: Bearer <token>
Accept: application/json

GET /api/courses
RESPONSE
{
    "message": "Courses retrieved successfully",
    "data": "data_courses"
}
```

### **Lecturer Features**

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/courses
{
    "lecturer_id" : "required",
    "name" : "required",
    "description" : "required",
}
RESPONSE
{
    "message": "Course created successfully",
    "data": "data_course"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

PUT /api/courses/{id}
{
    "lecturer_id" : "required",
    "name" : "string",
    "description" : "string",
}
RESPONSE
{
    "message": "Course updated successfully",
    "data": "data_course"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

DELETE /api/courses/{id}
RESPONSE
{
    "message": "Course deleted successfully",
}
```

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/materials
{
    "course_id" : "required",
    "title" : "required",
    "filepath" : "required|file|mimes:pdf",
}
RESPONSE
{
    "message": "Material uploaded successfully",
    "filepath": "material/filename"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/assignments
{
    "course_id" : "required",
    "title" : "required",
    "description" : "required",
    "deadline" : "required|date",
}
RESPONSE
{
    "message": "Assignment created successfully",
    "data": "data_assignment"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/submissions/{id}/grade
{
    "score" : "required",
}
RESPONSE
{
    "message": "Submission graded successfully",
    "data": "data_grade"
}
```

### **Student Features**

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/courses/{id}/enroll
RESPONSE
{
    "message": "Enrolled in course successfully",
    "data": "data"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

GET /api/materials/{id}/download
RESPONSE
{
    "Dowloading File"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/submissions
{
    "assignment_id" : "required",
    "student_id" : "required",
    "filepath" : "required|file|mimes:pdf|max:2048",
}
RESPONSE
{
    "message": "Submission submitted successfully",
    "data": "data_submission"
}
```

### **Discussions Features**

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/discussions
{
    "course_id" : "required",
    "user_id" : "required",
    "content" : "required",
}
RESPONSE
{
    "message": "Discussion created successfully",
    "data": "data"
}
```

```http
Authorization: Bearer <token>
Accept: application/json

POST /api/discussions/{id}/replies
{
    "discussion_id" : "required",
    "user_id" : "required",
    "content" : "required",
}
RESPONSE
{
    "message": "Reply added successfully",
    "data": "data"
}
```
