# Todo App

A Todo demo application, playing around with Laravel 10.

![TodoApp_screenshot.png](public/images/todo_app_screenshot.png)

Backend Framework:
- **Laravel v10.40.0 (PHP v8.3.1):** https://laravel.com/docs/10.x/installation#docker-installation-using-sail

Note:
- using server-side mysql database to save todo data


## Local Dev Installation

Clone the repo locally:

```sh
git clone https://github.com/wayjay4/laravel_todo_app.git todoapp
```

Go into todoapp dir:

```sh
cd todoapp
```

Install PHP dependencies (composer v2.6.6):

```sh
composer install
```

Install NPM dependencies (node v21.6.0, npm v10.3.0):

```sh
npm install
```

Build assets:

```sh
npm run build
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Two options to serve the Todo Application
- using php artisan serve and local database (see instructions below)
- using docker service container (see instructions below)

### Serve application with php artisan and local database

Create a local MySql database. You can also use another database (SQLite, Postgres), simply update your configuration accordingly.

- open .env file and change db settings as needed
- make database as needed

![env_conf_mysql_setting_screenshot.png](public/images/env_conf_mysql_setting_screenshot.png)

![mysql_make_database_screenshot.png](public%2Fimages%2Fmysql_make_database_screenshot.png)

Run database migrations and seeder:

```sh
php artisan migrate:fresh --seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit Todo App in your browser!

---

### Serve application with Docker/Sail

- open .env file and change db settings as needed

![env_conf_mysql_setting_screenshot.png](public/images/env_conf_mysql_setting_screenshot.png)

Install Docker

Make sure you have installed and started Docker Desktop Application:
- https://www.docker.com/

Install Sail

```sh
php artisan sail:install
```

Start Docker/Sail:

```sh
./vendor/bin/sail up
```
Configuring A Shell Alias for 'sail' (optional)

- https://laravel.com/docs/10.x/sail#configuring-a-shell-alias


```sh
sail up
```

Run database migrations and seeder for db service container:

```sh
sail artisan migrate:fresh --seed
```

You're ready to go! Visit Todo App in your browser!:

http://localhost/
