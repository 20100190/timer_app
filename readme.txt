#install
composer update
php artisan serve


#DB
mysql -u root -p
create database budget_webform;
php artisan migrate

#テーブル定義が変わった場合
php artisan migrate:refresh

#seeder
php artisan db:seed



#エラー時
composer dump-autoload

#login
http://127.0.0.1:8000/budget/login

#予算入力
http://127.0.0.1:8000/budget/enter

#予算照会
http://127.0.0.1:8000/budget/show
