# Admin panel for Laravel 5.2

## Intallation

- Install the package:

```
composer require keyhunter/administrator
```

- Add **ServiceProvider.php** to the ``` $providers ``` in *{root_project}\config\app.php* :

```
'providers' => [
    // ...
    Keyhunter\Administrator\ServiceProvider::class
];
```

- Remove **DatabaseSeeder.php** from *{root_project}\database\seeds*
and remove create_users_table from *{root_project}\database\migrations* if exists and publish files from ``` /vendor ``` :

```
php artisan vendor:publish
```

- Migrate the required tables:

```
php artisan migrate
```

- Seed the initial data to tables:

```
php artisan db:seed
```
If you get the error on ``` db:seed ``` command use ``` composer dump-autoload ``` and repeat ```db:seed``` again

- Done. Now go to ``` localhost:8000\admin ``` to login use:
```
login: keyhunter@gmail.com
pass: admin123
```