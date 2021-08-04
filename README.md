## Prerequisite :

1. xxamp - <a href="https://www.apachefriends.org/download.html" target="_blank">https://www.apachefriends.org/download.html</a> 

2. Mongodb - <a href="https://www.mongodb.com/try/download/community" target="_blank">https://www.mongodb.com/try/download/community</a> 

3. Composer - <a href="https://getcomposer.org/download/" target="_blank">https://getcomposer.org/download/</a> 

## Documentation

1. Swagger - <a href="http://localhost:8000/api/documentation" target="_blank">http://localhost:8000/api/documentation</a> 

2. Postman - <a href="https://www.getpostman.com/collections/3e68f0f2144e9e431183" target="_blank">https://www.getpostman.com/collections/3e68f0f2144e9e431183</a> 


## INSTALLATION :

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


