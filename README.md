# LaravelTestTaskJennylynLee


## Build Setup

``` bash
# install composer dependencies
composer update

# remove cache
php artisan config:cache

# create new database 
create database name:-laravel-envoyer

# to add table in database
run command :- php artisan migrate 

# remove config cache
run php artisan config:clear	

# generate auth token
php artisan passport:install

# run this command to start project
php artisan serve

# login url
http://127.0.0.1:8000/login

# In login page have email box using that user can add email. If added email alredy exist in table then it will send activation link otherwise it will insert into database then  it will send activation link.

# On click that activation link is 10 minitu old then it will not work also that link will not open second time.

# If activation link is correct then it will return auth token, token type and uuid.

# After get that data user have use http://127.0.0.1:8000/dashboard url.

# In that link user have  to pass token type, auth token and uuid like :- https://www.screencast.com/t/7pbaLv4pme

# If you don't want to migrate then I have added sql file you can use that.
```
