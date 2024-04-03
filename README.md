Run Project


install libs: composer install

php artisan key:generate
php artisan storage:link

Sửa thông tin kết nối db trong file .env:
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bvkienanhp_mdm
DB_USERNAME=root
DB_PASSWORD=root


run: php artisan serve
