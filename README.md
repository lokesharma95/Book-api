### Prerequisite :

1. download and install xampp with php version higher than 10

```
https://www.apachefriends.org/download.html
```

2. Download and install Mongodb with version higher than 5.0.1

```
https://www.mongodb.com/try/download/community
```

3. go to following website for issue related installation of mongo

```
https://docs.mongodb.com/manual/tutorial/install-mongodb-on-windows/
```

4. Install composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
```

### INSTALLATION :

#### Install locally

1. clone the git repository

```
git clone https://github.com/lokesharma95/Book-api.git
```

2. Run composer command to install project dependencies

```
composer install
```

3. Copy .env.example file to .env on the root folder

```
copy .env.example .env
```

4. migrate database

```
php artisan migrate
```

5. Run php server using

```
php artisan serve
```

6. Run test cases using

```
.\vendor\bin\phpunit.bat
```

7. Endpoints are

```
http://127.0.0.1:8080/api/books/
```

8. for swagger api documentation

```
http://localhost:8000/api/documentation
```


