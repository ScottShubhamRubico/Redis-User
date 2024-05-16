# Update table with redis server

### Requirement
*  If table data is not in the Redis server, add the table data to the Redis server and then display it in the app.

### Uses 
* RedisUserServiceProvider
  * Inside the boot method, If the user table data is not found in redis, then the user data will be added to redis.
  * inside the controller return user values from redis in index method.
  * tested with 10000 users.
### Prerequisites

* php v8.2, [see](https://laravel.com/docs/installation) Laravel specific requirements
* Apache v2.4 with `mod_rewrite`
* MySql v8.0.x
* [Composer](https://getcomposer.org) v2.5

### Setup

* Clone this repo, checkout to the most active branch
* Grant write permissions on `storage` and `bootstrap/cache` directories
* Create a config file and update environment variables

```bash
cp .env.example .env
```
* Install Composer

```bash
compser install
```
* Migrate and seed the database

```bash
php artisan migrate:fresh --seed
```


* Publish the assets

```bash
php artisan vendor:publish --tag=laravel-assets --force
```

* Clear cache

```bash
php artisan cache:clear
```

* Run Server

```bash
php artisan server
```



