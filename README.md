# laravel-test-backend

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laraveluser
DB_PASSWORD=laravelpassword

## Artisan commands

docker-compose exec app php artisan make:model Customer --all

docker-compose exec app php artisan migrate:fresh --seed

docker-compose build
docker-compose up -d


docker-compose exec app composer require "darkaonline/l5-swagger"
