The configuration file will be created before package publish:

```bash
$ php artisan vendor:publish --provider="Matmper\Providers\RepositoryProvider"
```

Config file: `config/repository.php`

```php
'default' => [

    // paginate default count per page by default
    'paginate' => env('REPOSITORY_DEFAULT_PAGINATE', 25),

    // get results with trashed by default
    'with_trashed' => env('REPOSITORY_WITH_TRASHED', false),
    
],
```