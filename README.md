# task-manager
this a laravel api project for a tasks management system build as task for job application

first make sure that php-8.3 and the common extensions are installed on you system
then make sure that composer latest version is installed 

now clone this repo in the local machine
navigate to the folder and run the composer command to install all dependencies
```sh
composer install
```
copy the .env.example file and fill it with the configuration for your system

```sh
cp -p .env.example .envs
```
after that run to generate the app key
```sh
php artisan key:gen
``` 

by default the app use sqlite for database (as it's just a simple task)

i will continue as we use sqlite for db

now we create empty sqlite file 
```sh
touch database/database.sqlite
```

now run this command to migrate the database
```sh
php artisan migrate
```

now run this command to seed the database with couple of users and random tasks
```sh
php artisan db:seed
```

now we can run this command to run couple of tests in the app
```sh
php artisan test
```

to test the api endpoints i attached a postman collection you can download it and load from post man on your machine ([Task Manager.postman_collection.json](https://raw.githubusercontent.com/abdalhalem-alfakhrany/task-manager/refs/heads/master/Task%20Manager.postman_collection.json))


this is a dumped erd from db software for the database used in this application ![ERD](https://github.com/abdalhalem-alfakhrany/task-manager/blob/master/database.png?raw=true)
