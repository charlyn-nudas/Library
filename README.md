#**Library_API**

###**📚 OVERVIEW ** 
A fully-featured RESTful API for managing library operations, including user authentication, book management, and author collections. Built on the Slim PHP framework with a MySQL backend, this API is designed to be scalable, secure, and extendable.  

It uses JWT (JSON Web Tokens) for authentication, ensuring secure access to resources, and provides modular endpoints for managing users, books, authors, and collections. 

###**📋 FEATURES** 
1. **Secure Authentication**: Implements JWT for token-based user authentication.  
2. **User Management**: Register and log in users.  
3. **Book Management**: Add, update, delete, and list books.  
4. **Author Management**: Manage author data, including relationships with books.  
5. **Scalable Design**: Easily add new features or endpoints as requirements evolve.  
6. **Error Handling**: Comprehensive error messages for debugging and troubleshooting.  

###**🛠 SYSTEM REQUIREMENTS**
- 'PHP': Version 8.0 or higher (with extensions: `pdo_mysql`, `mbstring`, `json`)  
- 'Composer': PHP dependency manager  
- 'MySQL/MariaDB': Relational database for storing library data  
- 'Apache/Nginx': Web server for hosting the API  
- 'Postman/Thunder Client': For testing the API endpoints
