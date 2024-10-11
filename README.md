# Kimia Darvish Nouri

## Name
PHP Blog managment system

## Description
This project is a simple API-based backend that provides basic functionality for user authentication and blog management using Laravel. The system allows users to log in and manage blogs (create, edit, publish, and delete). Users can assign tags to posts and schedule posts to be published at a future time. Logged-in users can like or unlike posts, as well as add and like comments. When a user publishes a post, an email is sent to all users with a link to the published post. Additionally, users can search for specific content in the title, author name, or body of posts and view their notifications through a simple and intuitive interface. There is also a feature for admins to generate weekly reports on posts, either automatically or at a specified time.

## Features
- **User Authentication:** 
    - User Login and Registraction 
    
- **Blog Management:**
    - Create new blog posts
    - Edit existing blog posts
    - Delete blog posts
    - Like/Remove likes of posts
    - Add Comment
    - Like/Remove likes of comments
    - Publish posts in the future
    - Get users that like a specific post
    - Get all tags and number of posts that use each tag

- **Blog Management:**
    - User can see their notifications
    - An email will sent to all users when a post is published

- **Weekly Reports:**
    - System will take weekly report of posts for admins
    - Admins can generate weekly reports starting from a specific date (using a command)
    - for taking export using command open a new terminal and run this:
    **php artisan post-export date**
    example: php artisan post-export 2024-08-25

## Installation
1. **Clone the Repository:**
   git clone [Repository](http://89.32.250.70/internship/backend/kimia-darvish-nouri.git)
2. **cd yourprojectname**
3. **make a copy of .env.example file and change it to .env and setup your configuration**
    - on the **APP_URL** set the path to your project
    - setup your database configuration
    - set **CACHE_STORE=redis**
    - set **REDIS_CLIENT=predis**
    - set the **MAIL SERVER** cofiguration, I am setting it using Gmail
        - MAIL_MAILER=smtp
        - MAIL_HOST=smtp.gmail.com
        - MAIL_PORT=587
        - MAIL_USERNAME=kdarvishnoori@gmail.com
        - MAIL_PASSWORD=mymlblfrtcfsvxzf
        - MAIL_ENCRYPTION=tls
        - MAIL_FROM_ADDRESS=kdarvishnoori@gmail.com
        - MAIL_FROM_NAME="SokanAcademy"  
    - open a new terminal and run : **php artisan schedule:work**
    - open a new terminal and run : **php artisan queue:work**
    - open a new terminal and run : **php artisan serve**  (if you are not  using apache) 
     
4. **Open the terminal and run below commands:**
    - composer install 
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed --class=AdminSeeder

## API Endpoints
**POST Method:**
- localhost/project_name/public/api/register
- localhost/project_name/public/api/login
- localhost/project_name/public/api/logout
- localhost/project_name/public/api/posts
- localhost/project_name/public/api/likes
- localhost/project_name/public/api/comments/post_id
- localhost/project_name/public/api/publish/post_id

**GET Method:**
- localhost/project_name/public/api/posts
- localhost/project_name/public/api/posts/post_id
- localhost/project_name/public/api/likes/post_id : read likes
- localhost/project_name/public/api/search
- localhost/project_name/public/api/tags : read tags
- localhost/project_name/public/api/notifications
- localhost/project_name/public/api/view-exports
- localhost/project_name/public/api/download-exports
- localhost/project_name/public/api/sokanAcademy

**PUT Method:**
- localhost/project_name/public/api/posts/id

**DELETE Method:**
- localhost/project_name/public/api/posts/id


## Prerequisites
- **Web Server:** Xamp, Nginx, or any web server that supports PHP
- **PHP:** Version 8.2 or higher
- **Apach:** Version 2.4.58 or higher
- **phpMyAdmin:** Version 5.2.1 or higher
- **laravel:** Version 11.9 or higher
- **maatwebsite/excel:** Version 3.1 or higher

