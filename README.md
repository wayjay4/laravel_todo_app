To install the Todo application:
1. adjust the database setting in the .env file for your system (see lines #11-14)
2. make sure database exists on you system
3. run '>>php artisan migrate' to make db tables
4. run '>>composer install'
5. run '>>php artisan serve'

Here is a list of files I worked with:
1. app/Http/Controllers/TaskController.php
2. app/Http/Requests/StoreTaskRequest.php
3. app/Models/Task.php
4. database/migrations/2023_12_18_235435_create_tasks_table.php
5. resources/views/tasks/index.blade.php
6. routes/web.php
