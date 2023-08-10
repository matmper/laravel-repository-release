# Configuration

The configuration file is generated during package publishing:

```bash
$ php artisan vendor:publish --provider="Matmper\Providers\RepositoryProvider"
```

The command will generate the `config/repository.php` file with default settings

```php
<?php # config/repository.php

    'default' => [

        // default limit of results per page
        'paginate' => env('REPOSITORY_DEFAULT_PAGINATE', 25),

        // true = forces the use of the withTrashed() method by default
        'with_trashed' => env('REPOSITORY_WITH_TRASHED', false),
        
    ],
```