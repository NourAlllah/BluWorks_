# BluWorks_Task_
This Laravel web application provides a sample API for Bluworks, an HR system for service industry workers. It focuses on managing worker clock-in functionalities through a mobile-friendly web application.

*Features*
- User Authentication System for secure access
  - Registration functionality for new users.
  - Login functionality for authorized users.
- Allows workers to clock in using a mobile app (simulated here).
- Tracks all worker clock-ins details like timestamp, location (latitude & longitude).
- Validates clock-in location to ensure it's within a 2km radius of a designated work location.
- Provides an endpoint for workers to view their clock-in history.



*Technology Stack*
- PHP
- Laravel Framework
- MySQL Database
- Javascript
- HTML/CSS


*API Endpoints*
- POST /worker/clock-in (for clocking in)
- GET /worker/clock-ins?worker_id=123 (for viewing clock-in history)

*Components Used*

Migrations:
- Database migrations are used to manage database schema changes and ensure consistency across different environments. Migrations are used to create and modify database tables for tasks, users, and statistics.

Seeds:
- Database seeding is used to populate the database with initial data. Seeders are used to create sample users and admins for testing purposes.

---------------------

*Setup and Installation*

1- Clone the repository:

        git clone https://github.com/NourAlllah/BluWorks_.git

2- Update dependencies:

       composer update    

3- Configure the .env File:
  
      cp .env.example .env

      # Update the following lines in the .env file
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_username
        DB_PASSWORD=your_database_password
      
4- Generate Application Key:

        php artisan key:generate
        
5- php artisan migrate:

        php artisan migrate

6- Seed the database:

        php artisan db:seed

7- Install Vite

        npm install vite --save-dev

8- Start the development server:

        php artisan serve
        
9- php artisan serve

        npm run dev
