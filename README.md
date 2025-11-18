# Диплом
Дипломный проект представляет собой создание сайта для бронирования онлайн билетов в кинотеатр и разработка информационной системы для администрирования залов, сеансов и предварительного бронирования билетов.

## Технические требования
php versoin 8.4.11
Composer version 2.8.11
laravel framework 12.x
СУБД mysql
node.js >= 18

## Установка mysql через podman
```bash
podman run -d \
  --name mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=laravel \
  -e MYSQL_USER=laravel \
  -e MYSQL_PASSWORD=laravel \
  -p 3306:3306 \
  docker.io/mysql:lts

podman ps
```
проверить, что  mysql запущен

## Установка проекта
```bash
git clone https://github.com/sleeper-g/diplom.git
cd diplom
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed # создает admin
npm install
npm run build
php artisan serve
```
Приложение будет доступно по адресу:
http://127.0.0.1:8000

## .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

## users

### admin
```
php artisan migrate --seed
```
создает

email:admin@cinema.local

password:password
 
### guest

http://127.0.0.1:8000/register