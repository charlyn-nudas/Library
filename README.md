
# **Library_API**

### **📚 OVERVIEW** 
A fully-featured RESTful API for managing library operations, including user authentication, book management, and author collections. Built on the Slim PHP framework with a MySQL backend, this API is designed to be scalable, secure, and extendable.  

It uses JWT (JSON Web Tokens) for authentication, ensuring secure access to resources, and provides modular endpoints for managing users, books, authors, and collections. 

### **📋 FEATURES** 
  
  &nbsp;&nbsp;&nbsp; 1. **Secure Authentication**: Implements JWT for     token-based user authentication.  
  &nbsp;&nbsp;&nbsp; 2. **User Management**: Register and log in users.  
  &nbsp;&nbsp;&nbsp; 3. **Book Management**: Add, update, delete, and list books.  
  &nbsp;&nbsp;&nbsp; 4. **Author Management**: Manage author data, including relationships with books.  
  &nbsp;&nbsp;&nbsp; 5. **Scalable Design**: Easily add new features or endpoints as requirements evolve.  
  &nbsp;&nbsp;&nbsp; 6. **Error Handling**: Comprehensive error messages for debugging and troubleshooting.  

### **🛠 SYSTEM REQUIREMENTS**
- `PHP`: Version 8.0 or higher (with extensions: `pdo_mysql`, `mbstring`, `json`)  
- `Composer`: PHP dependency manager  
- `MySQL/MariaDB`: Relational database for storing library data  
- `Apache/Nginx`: Web server for hosting the API  
- `Postman/Thunder Client`: For testing the API endpoints


### **🌟 ENDPOINTS**

#### **Endpoint 1**: *User Registration*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/user/register` | Registers a single user or multiple users |

**Request Body** (JSON):

For single user:

```http
{
  "username": "user1",
  "password": "password123"
}
```
For multiple users:

```http
[
  { 
    "username": "user1", 
    "password": "password123" 
  },
  
  { 
    "username": "user2", 
    "password": "password456" 
  }
]
```
**Response**:

- Successful Response:

    Single user:

    ```http
    {
      "status": "success",
      "username": "user1",
      "data": null
    }
    ```

    Multiple users:
    ```http
    [
      {
        "status": "success",
        "username": "user1",
        "data": null
      },
      {
        "status": "success",
        "username": "user2",
        "data": null
      }
    ]
    ```
- Failed Responses:

    

  Missing Fields:

    ```http
    {
      "status": "fail",
      "username": "",
      "data": {
        "Message": "Username and password cannot be empty."
      }
    }
    ```

    Username Already Exists:
    ```http
    {
      "status": "fail",
      "username": "user1",
      "data": {
        "Message": "Username already taken!"
      }
    }
    ```



 
#### **Endpoint 2**: *User Login*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/user/login` | Logs in a user and generates a JWT token |

**Request Body** (JSON):


```http
{
  "username": "user1",
  "password": "password123"
}
```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzQ3MzUsImV4cCI6MTczMzQ3ODMzNSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.Y0PS4Dg4wN0qrMuxUCz4VcL728bXRdf_amYi3TyJelI"
    }
    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Access Denied."
      }
    }
    ```

    Invalid Credentials:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid username or password"
      }
    }
    ```

    Login Unsuccessful (Server Error):
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Login failed."
      }
    }
    ```

#### **Endpoint 3**: *Add Author (Admin Only)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/add/authors` | Only admin can add new authors to the library database.  |

**Request Body** (JSON):


```http
{
  "authors": ["Author 1", "Author 2", "Author 3"],
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzQ3MzUsImV4cCI6MTczMzQ3ODMzNSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.Y0PS4Dg4wN0qrMuxUCz4VcL728bXRdf_amYi3TyJelI"
}
```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "message": "Authors processed successfully.",
      "successful_authors": "Author 1, Author 2, Author 3",
      "failed_authors": "",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzQ5ODQsImV4cCI6MTczMzQ3ODU4NCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.9rHMozUe1KJlksuwpFEOKyQP936YrMZwLa1mgnNgW_E"
    }
    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "message": "Access denied, only admins can add authors."
    }
    ```

    

    Token Already Expired:
    ```http
    {
      "status": "fail",
      "message": "Expired token"
    }
    ```
  #### **Endpoint 4**: *Update Author (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/update/authors` | Only admin can update an existing author's details in the library database. |

**Request Body** (JSON):


```http
{
  "authorid": 322,   
  "authorname": "Author 1",  
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzQ5ODQsImV4cCI6MTczMzQ3ODU4NCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.9rHMozUe1KJlksuwpFEOKyQP936YrMZwLa1mgnNgW_E"        
}
```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "message": "Author updated successfully.",
      "updated_author": {
        "authorid": 322,
        "authorname": "Author 1A"
      },
    "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzYyNDEsImV4cCI6MTczMzQ3OTg0MSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.Y2vEUDAOf9C0nW1QWeecblx7ni-siwXoM0CKl7pQcw8"
    }
    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "message": "Access denied, only admins can add authors."
    }
    ```

    

    Invalid or Expired Token:
    ```http
    {
      "status": "fail",
      "message": "Invalid or outdated token."
    }
    ```

    Invalid Author ID:
    ```http
    {
      "status": "fail",
      "message": "Provided author ID does not exist."
    }
    ```

  #### **Endpoint 5**: *Delete Author (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `DELETE` | `/delete/authors` |  Only admin can delete an existing author from the library database. |

**Request Body** (JSON):


```http
{
  "authorid": 322,  
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0NzYyNDEsImV4cCI6MTczMzQ3OTg0MSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.Y2vEUDAOf9C0nW1QWeecblx7ni-siwXoM0CKl7pQcw8" 
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "message": "Author deleted successfully.",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0Nzc1MjQsImV4cCI6MTczMzQ4MTEyNCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.dem8evoiilE3W_x0vYGuYYXk-KffsDEYcdC6nzgszCQ"
    }
    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "message": "Access denied, only admins can add authors."
    }
    ```

    

    Invalid or Expired Token:
    ```http
    {
      "status": "fail",
      "message": "Invalid or outdated token."
    }
    ```

    Invalid Author ID:
    ```http
    {
      "status": "fail",
      "message": "Provided author ID does not exist."
    }
    ```
#### **Endpoint 6**: *Display all Authors *


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/dispaly/authors` |  Retrieves a list of all authors in the library database|

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0Nzc1MjQsImV4cCI6MTczMzQ4MTEyNCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.dem8evoiilE3W_x0vYGuYYXk-KffsDEYcdC6nzgszCQ"  
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "authors": [
        {
          "authorid": 323,
          "authorname": "Author 2"
        },
        {
          "authorid": 324,
          "authorname": "Author 3"
        }
      ],
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0Nzg0NDQsImV4cCI6MTczMzQ4MjA0NCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.xY2nFwv-IvxIdyPH7gKzNNnTv_5gqEmq90nOrVQIyYw"
    }
    ```

    
- Failed Responses:

    
    Invalid or Expired Token:
    ```http
    {
      "status": "fail",
      "message": "Invalid or outdated token."
    }
    ```

    No Authors Found:
    ```http
    {
      "status": "fail",
      "message": "No authors found."  
  }
    ```
  #### **Endpoint 7**: *Display All Users (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/displayall/users` |  Only admin can update an existing author's details in the library database. |

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0Nzg0NDQsImV4cCI6MTczMzQ4MjA0NCwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.xY2nFwv-IvxIdyPH7gKzNNnTv_5gqEmq90nOrVQIyYw"   
}
```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODA4MDIsImV4cCI6MTczMzQ4NDQwMiwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.-y80WPGSVPnLMlePU5hmiUYXy-elnBu3lS8RVp3l968",
      "data": [
        {
          "username": "user1",
          "email": "",
          "created_at": "2024-12-06 18:26:32"
        },
        {
          "username": "user2",
          "email": "",
          "created_at": "2024-12-06 18:26:32"
        }
      ]
    }
    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Access denied, only admins can view list of users."  
      }
    }
    ```

    

    Invalid or Expired Token:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."  
      }
    }
    ```

    No Users Found:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No user accounts found."  
      }
    }    
    ```

  #### **Endpoint 8**: *Delete User (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `DELETE` | `/delete/users` |   Only admin can delete a user from the library system |

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODA4MDIsImV4cCI6MTczMzQ4NDQwMiwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.-y80WPGSVPnLMlePU5hmiUYXy-elnBu3lS8RVp3l968",   
  "userid": 125  
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODE5ODEsImV4cCI6MTczMzQ4NTU4MSwiZGF0YSI6eyJ1c2VyaWQiOjEyNSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.Cjrej07BYMFviTluibE33SPcxuKCJJVfjTNrOEBlUpw"
    }

    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Access denied, only admins can delete a user."  
      }
    } 
    ```

    Admin can't be Deleted:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Admin accounts can't be deleted."  
      }
    }
    ```
    

    Invalid or Expired Token:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."  
      }
    }
    ```

    No User ID Found:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Provided user ID does not exist."  
      }
    }    
    ```

#### **Endpoint 9**: *Add Book (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/add/books` |   Allows admin to add multiple books to the library|

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODE5ODEsImV4cCI6MTczMzQ4NTU4MSwiZGF0YSI6eyJ1c2VyaWQiOjEyNSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.Cjrej07BYMFviTluibE33SPcxuKCJJVfjTNrOEBlUpw",
  "books": [
    {
      "title": "Book Title Two",
      "author": "Author 2",
      "genre": "Fiction"
    },
    {
      "title": "Book Title Three",
      "author": "Author 3",
      "genre": "Fantasy"
    }
    
  ]
}


```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "message": "Books successfully added.",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODQzODUsImV4cCI6MTczMzQ4Nzk4NSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.v4ipo4XsWbirl4iS-IlMr-heY7D5eSeCAy2B7X85nRg"
    }

    ```

    
- Failed Responses:

    Unauthorized Access:

    ```http
    {
      "status": "fail",
      "message": "Access denied, only admins can add books."
    }

    ```
    

    Invalid or Expired Token: 
    ```http
    {
      "status": "fail",
      "message": "Invalid or outdated token."
    }
    ```

#### **Endpoint 10**: *Update Books (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `POST` | `/update/books` |   Allows admin to update details of an existing book in the library|

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0ODQzODUsImV4cCI6MTczMzQ4Nzk4NSwiZGF0YSI6eyJ1c2VyaWQiOjEyNCwibmFtZSI6InVzZXIxIiwiYWNjZXNzX2xldmVsIjoiYWRtaW4ifX0.v4ipo4XsWbirl4iS-IlMr-heY7D5eSeCAy2B7X85nRg",
  "bookCode": "CD456",
  "title": "Book Title Two",
  "author": "Author 2",
  "genre": "Fantasy"
}


```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTAxMDAsImV4cCI6MTczMzQ5MzcwMCwiZGF0YSI6eyJ1c2VyaWQiOjEyNiwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.V_xiK7CYb1I_w4sl1Yur6VxZKv77wI2wk-W4IUh8EyM"
    }

    ```

    
- Failed Responses:

    Unauthorized Access:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Access denied, only admins can update books."  
      }
    }
    ```
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }


    ```
    

    Book Code Validation Error: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid Book Code."
      }
    }

    ```
    Update Error: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No fields to update."
      }
    }

    ```

  #### **Endpoint 11**: *Delete Books (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `DELETE` | `/delete/books` |   Allows admin to delete a book from the library.|

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTAxMDAsImV4cCI6MTczMzQ5MzcwMCwiZGF0YSI6eyJ1c2VyaWQiOjEyNiwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.V_xiK7CYb1I_w4sl1Yur6VxZKv77wI2wk-W4IUh8EyM",
  "bookCode": "CD456"
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTU5NzAsImV4cCI6MTczMzQ5OTU3MCwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InVzZXI2IiwiYWNjZXNzX2xldmVsIjoiIn19.zfwJfdFN4LigxoFaOa6zBQagFqNWbSJ_jFVkBeUfzgQ"
    }

    ```

    
- Failed Responses:

    Unauthorized Access:
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Access denied, only admins can delete books."  
      }
    }
    ```
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }

    ```
    

    Book Code Validation Error: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid Book Code."
      }
    }

    ```

  #### **Endpoint 12**: *Display All Books (Admin)*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/displayall/books` |   Retrieves all books from the library, including their details and associated authors|

**Request Body** (JSON):


```http
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTU5NzAsImV4cCI6MTczMzQ5OTU3MCwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InVzZXI2IiwiYWNjZXNzX2xldmVsIjoiIn19.zfwJfdFN4LigxoFaOa6zBQagFqNWbSJ_jFVkBeUfzgQ"
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTY3ODQsImV4cCI6MTczMzUwMDM4NCwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiIifX0.30nzYS5luJ8TxxF6HrCfxcEea4KhTtdphqs1r5Q_Yd4",
      "data": [
        {
          "bookid": 143,
          "title": "Book Title Three",
          "genre": "Science",
          "bookCode": "EF789",
          "authorid": 324,
          "authorname": "Author 3"
        },
        {
          "bookid": 144,
          "title": "Book Title Four",
          "genre": "Mystery",
          "bookCode": "GH012",
          "authorid": 325,
          "authorname": "Author 4"
        }
      ]
    }

    ```

    
- Failed Responses:

  
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }

    ```
    

    No Author Found: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No such author exists."
  }
}

    ```

  #### **Endpoint 13**: *Display Books by Author*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/display/auhtorsbooks` |   Retrieves all books by a specific author|

**Request Body** (JSON):


```http
{
  "authorname": "Author 3",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTY3ODQsImV4cCI6MTczMzUwMDM4NCwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiIifX0.30nzYS5luJ8TxxF6HrCfxcEea4KhTtdphqs1r5Q_Yd4"
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTc5MzYsImV4cCI6MTczMzUwMTUzNiwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiIifX0.Ousvd2xIt1PzaTxVP7xZu0elefzxnw1F6MGUMWzt1AM",
      "data": [
        {
          "bookid": 143,
          "title": "Book Title Three",
          "genre": "Science",
          "bookCode": "EF789",
          "authorid": 324,
          "authorname": "Author 3"
        }
      ]
    }

    ```

    
- Failed Responses:

  
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }

    ```
    

    Author Not Found: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No such author exists."
      }
    }

    ```

  #### **Endpoint 14**: *Display Books by Title*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/display/titlebooks` |   Retrieves information about books that match the specified title|

**Request Body** (JSON):


```http
{
  "booktitle": "Book Title Three",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTc5MzYsImV4cCI6MTczMzUwMTUzNiwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiIifX0.Ousvd2xIt1PzaTxVP7xZu0elefzxnw1F6MGUMWzt1AM"
}


```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM1MDA1MDUsImV4cCI6MTczMzUwNDEwNSwiZGF0YSI6eyJ1c2VyaWQiOjEzMCwibmFtZSI6InVzZXI2aCIsImFjY2Vzc19sZXZlbCI6IiJ9fQ.vAOO_dnlyy80BW7GR4a_QJl-jQbOkOpiuAuCPIRW-W0",
      "data": [
        {
          "bookid": 143,
          "title": "Book Title Three",
          "genre": "Science",
          "bookCode": "EF789",
          "authorid": 324,
          "authorname": "Author 3"
        }
      ]
    }

    ```

    
- Failed Responses:

  
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }

    ```
    

    Book Not Found: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No such book exists."
      }
    }

    ```

#### **Endpoint 15**: *Display Books by Genre*


| Method | URL     | Description                |
| :-------- | :------- | :------------------------- |
| `GET` | `/display/genrebooks` |   Lists all books categorized under a specific genre|

**Request Body** (JSON):


```http
{
  "bookgenre": "Science",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM1MDA1MDUsImV4cCI6MTczMzUwNDEwNSwiZGF0YSI6eyJ1c2VyaWQiOjEzMCwibmFtZSI6InVzZXI2aCIsImFjY2Vzc19sZXZlbCI6IiJ9fQ.vAOO_dnlyy80BW7GR4a_QJl-jQbOkOpiuAuCPIRW-W0"
}

```

**Response**:

- Successful Response:

    

    ```http
    {
      "status": "success",
      "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTk2NTgsImV4cCI6MTczMzUwMzI1OCwiZGF0YSI6eyJ1c2VyaWQiOjEyOSwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiIifX0.f1j6b4PuwdlfsP59NWfvWvBArIN_5syaDWLpvn7ZpkQ",
      "data": [
        {
          "bookid": 143,
          "title": "Book Title Three",
          "genre": "Science",
          "bookCode": "EF789",
          "authorid": 324,
          "authorname": "Author 3"
        }
      ]
    }

    ```

    
- Failed Responses:

  
    
    Token Error:

    ```http
    {
      "status": "fail",
      "data": {
        "Message": "Invalid or Outdated Token."
      }
    }

    ```
    

    Book Genre Not Found: 
    ```http
    {
      "status": "fail",
      "data": {
        "Message": "No such book genre exists."
      }
    }

    ```

  
## Database Schema

#### **Authors Table**

| Column | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `authorid` | `int(Primary Key)` | Unique author ID  |
| `authorname`| `char(255)` | Author's name |

#### **Books Table**

| Column | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `bookid` | `int(Primary Key)` | Unique book identifier|
| `title`| `char(255)` | Book title  |
| `genre` | `char(255)` | Book genre  |
| `authorid` | `int(9)` | Associated author ID |
| `bookCode` | `varchar(5)` | Unique book code  |

#### **Books_collection Table**

| Column | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `collectionid` | `int(Primary Key)` |Unique collection identifier|
| `bookid`| `int(9)` | Associated book ID |
| `authorid` | `int(9)` | Associated author ID  |

#### **Users Table**

| Column | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `userid` | `int(Primary Key)` | Unique user ID  |
| `username`| `char(255)` | Unique username |
| `password` | `text` | Hashed password  |
| `access_level` | `varchar(10)` | User role (`admin`or `null`) |
| `token` | `text` | JWT token  |
| `email` | `varchar(50)` | User's email address |
| `created_at` | `datetime` |  Timestamp indicating when the user was created |


## Notes
- Ensure to replace the key used for encoding JWT with a secure, randomly generated secret key.
- JWT tokens expire after 1 hour and need to be refreshed for continued use.
 

  
  

