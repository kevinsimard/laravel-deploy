<?php

namespace KevinSimard\Deploy\Command\Task;

use Collective\Remote\RemoteManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeployTask extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'deploy:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy Laravel application through SSH.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $remote = $this->option('remote');
        $commands = $this->prepareCommands();

        $remoteManager = new RemoteManager($this->laravel);
        $remoteManager->connection($remote)->run($commands);
    }

    /**
     * Prepare a set of commands.
     *
     * @return array
     */
    protected function prepareCommands()
    {
        $commands = [];

        // change current directory
        $commands[] = "cd {$this->argument('root')}";

        if (!$this->option('no-maintenance')) {
            // set maintenance mode ON
            $commands[] = 'php artisan down';
        }

        // force checkout and merge new commits
        $commands[] = 'git checkout -f';
        $commands[] = 'git pull -f';

        if (!$this->option('no-composer')) {
            // update composer
            $commands[] = 'composer self-update';

            // install dependencies
            $commands[] = 'composer install\
                --no-dev\
                --no-progress\
                --prefer-source\
                --no-interaction\
                --optimize-autoloader';
        }

        if (!$this->option('no-migration')) {
            // run database migrations
            $commands[] = 'php artisan migrate --force';
        }

        if (!$this->option('no-maintenance')) {
            // set maintenance mode OFF
            $commands[] = 'php artisan up';
        }

        return $commands;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['root', InputArgument::REQUIRED, 'Remote path to project directory'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['remote', null, InputOption::VALUE_OPTIONAL, 'Remote connection/group name.'],
            ['no-composer', null, InputOption::VALUE_NONE, 'Do not install dependencies.'],
            ['no-migration', null, InputOption::VALUE_NONE, 'Do not run migration files.'],
            ['no-maintenance', null, InputOption::VALUE_NONE, 'Do not use maintenance mode.'],
        ];
    }
}
