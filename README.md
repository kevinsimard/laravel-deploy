# Deploy Laravel Application
[![Code Climate](https://codeclimate.com/github/kevinsimard/laravel-deploy/badges/gpa.svg)](https://codeclimate.com/github/kevinsimard/laravel-deploy)

## Installation
Add ```Kevinsimard\Deploy\Command\Task\DeployTask``` to the list of commands in ```app/Console/Kernel.php```

```php
<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ...
        'Kevinsimard\Deploy\Command\Task\DeployTask',
    ];
...
```

Add/edit the  ```config/remote.php``` file

```php
<?php

return [
    // default connection name
    'default' => 'local',

    // connections
    'connections' => [
        'local' => [
            'host'      => '127.0.0.1',
            'username'  => 'username',
            'password'  => 'password',
            'key'       => '',
            'keytext'   => '',
            'keyphrase' => '',
            'agent'     => '',
        ],
    ],

    // connection groups
    'groups' => [
        'web' => ['local']
    ],
];
```

## Artisan Command
```bash
php artisan deploy:app <ROOT>
```

> The following options are available:
* **_--remote=\<CONNECTION>_**: Remote connection/group name
* **_--no-composer_**: Do not install dependencies
* **_--no-migration_**: Do not run migration files
* **_--no-maintenance_**: Do not use maintenance mode

## Code Structure
    ┌── src/
    │   └── Kevinsimard/
    │       └── Deploy/
    │           └── Command/
    │               └── Task/
    │                   └── DeployTask.php
    ├── .gitattributes
    ├── .gitignore
    ├── composer.json
    └── README.md
