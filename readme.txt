#DB
mysql -u root -p
create database budget_webform;

php artisan migrate
php artisan db:seed

#�N��
php artisan serve




#login
http://127.0.0.1:8000/budget/login

#�\�Z����
http://127.0.0.1:8000/budget/enter

#�\�Z�Ɖ�
http://127.0.0.1:8000/budget/show
