
# Installation Steps
This guide provides instructions for setting up and installing the Laravel application.



### 1. Clone the Repository
Clone the Laravel repository from the Git repository:

```git clone <repository_url>```


### 2. Install Composer Dependencies
Navigate to the project directory and install Composer dependencies:

```cd <project_directory>```

```composer install```


### 3. Create .env File
Copy the .env.example file to create a .env file:

```cp .env.example .env```


### 4. Generate Application Key
Generate a unique application key for your Laravel application:

```php artisan key:generate```


### 5. Generate JWT Secret
Generate a JWT secret key for JWT authentication:

```php artisan jwt:secret```


### 6. Configure Database Connection
Update the .env file with your database connection details:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```


### 7. Run Migrations
Run database migrations to create necessary database tables:

```php artisan migrate```

### 7. Usage:
After completing the installation steps, you can start using the Laravel application by running a local development server:

```php artisan serve```
