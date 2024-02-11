# FlorisBoard Addons Backend Documentation

Welcome to the official documentation for FlorisBoard Addons Backend. This guide provides comprehensive information on
setting up and running the Laravel application for the backend. Follow the steps below to get started with the
FlorisBoard Addons Backend.

## Table of Contents

* [How to Run the Backend](#how-to-run-the-backend)
* [Development](#development)
    * [Default Alias](#default-alias)
    * [Tools](#tools)
      * [Code Formatting](#code-formatting)
      * [Static Analysis](#static-analysis)
      * [Debug and dd](#debug)
      * [Testing](#testing)
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

3. **Configure & use these recommended alias:**

    ```bash
    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
    alias saila='sail artisan'
    alias sailp='sail bin pint'
    alias sails='sail bin phpstan analyze'
    ```

4. **Run Docker Containers:**

    ```bash
    sail up
    ```

5. **Generate Application Key:**

    ```bash
    saila key:generate
    ```

6. **Create Local Storage for File Uploads:**

    ```bash
    saila storage:link
    ```

7. **Migrate the Database and Seed with Fake Data (for development):**

    ```bash
    saila migrate && saila migrate:fresh --seed
    ```

## Development

### Default Alias

We recommend to configure these alias for easier development

```bash
    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
    alias saila='sail artisan'
    alias sailp='sail bin pint'
    alias sails='sail bin phpstan analyze'
```

### Tools

Make sure to configure the [default alias](#default-alias)

#### Code Formatting

We're using [laravel pint](https://laravel.com/docs/10.x/pint) for code formatting you can run the code formatting using

```bash
sailp
```

#### Static Analysis

We're using [larastan](https://github.com/larastan/larastan) for static analysis make sure to run it occasionally to
catch bugs

```bash
sails
```

#### Debug

When you want to debug a value instead of using the old ```dd``` you can take advantage
of [laravel ray](https://spatie.be/docs/ray/v1/introduction)

```php
ray(...$values);
```
and you can see the result on [http://localhost:8000](http://localhost:8000). It's an open source laravel ray compatible service called [buggregator](https://buggregator.dev/).

### Testing
We're using testing to make sure the logic works with different scenarios so make sure to use

```bash
saila test
```

to see if tests are passing

### UI Documentation Link

Access the API documentation by visiting the following link: [http://localhost/docs/api](http://localhost/docs/api)

### Admin Panel Link

Access the admin panel through the following link: [http://localhost/admin](http://localhost/admin)

#### Default Credentials

* **Email:** admin@email.com
* **Password:** password

### Mail Link

Access the Mailpit interface using the following link: [http://localhost:8025](http://localhost:8025)
