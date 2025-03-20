<?php

namespace Safia\Authcontroller\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeAuthControllerCommand extends Command
{
    protected $signature = 'make:auth-controller {name=AuthController}';
    protected $description = 'Create an authentication controller with register and login methods';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Http/Controllers/' . $name . '.php');

        if ($this->files->exists($path)) {
            $this->error('Controller already exists!');
            return;
        }

        $this->makeDirectory(dirname($path));
        $this->files->put($path, $this->buildClass($name));
        $this->info('Authentication controller created successfully.');
    }

    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get(__DIR__ . '/stubs/auth-controller.stub');

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['{{ namespace }}'],
            ['App\\Http\\Controllers'],
            $stub
        );

        return $this;
    }

    protected function replaceClass($stub, $name)
    {
        $class = str_replace('/', '\\', $name);

        return str_replace('{{ class }}', $class, $stub);
    }
}