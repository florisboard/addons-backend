# FlorisBoard Addons Backend Documentation

Welcome to the official documentation for FlorisBoard Addons Backend. This guide provides comprehensive information on setting up and running the Laravel application for the backend. Follow the steps below to get started with the FlorisBoard Addons Backend.

## Table of Contents

* [How to Run the Backend](#how-to-run-the-backend)
* [Additional Information](#additional-information)
    * [UI Documentation Link](#ui-documentation-link)
    * [Admin Panel Link](#admin-panel-link)
        * [Default Credentials](#default-credentials)
    * [Mail Link](#mail-link)

## How to Run the Backend

Before proceeding, ensure that Docker is installed on your system.

1. **Copy the .env file:**
    
    ```bash
    cp .env.example .env
    ```
    
2. **Install Composer Dependencies:**
    
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```
    
3. **Run Docker Containers:**
    
    ```bash
    ./vendor/bin/sail up
    ```
    
4. **Generate Application Key:**
    
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```
    
5. **Create Local Storage for File Uploads:**
    
    ```bash
    ./vendor/bin/sail artisan storage:link
    ```
    
6. **Migrate the Database and Seed with Fake Data (for development):**
    
    ```bash
    ./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan migrate:fresh --seed
    ```
    

## Additional Information

### UI Documentation Link

Access the API documentation by visiting the following link: [http://localhost/docs/api](http://localhost/docs/api)

### Admin Panel Link

Access the admin panel through the following link: [http://localhost/admin](http://localhost/admin)

#### Default Credentials

* **Email:** admin@email.com
* **Password:** password

### Mail Link

Access the Mailpit interface using the following link: [http://localhost:8025](http://localhost:8025)
