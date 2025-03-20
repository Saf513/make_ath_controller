<?php

namespace Safia\Authcontroller\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

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
        
        // Handle controllers in subdirectories
        $name = str_replace('\\', '/', $name);
        $path = App::basePath('app/Http/Controllers/' . $name . '.php');

        if ($this->files->exists($path)) {
            $this->error('Controller already exists!');
            return;
        }

        $this->makeDirectory(dirname($path));
        $stub = $this->buildClass($name);
        $this->files->put($path, $stub);
        $this->info('Authentication controller created successfully.');
        
        // Output the path for convenience
        $this->line('Controller created at: ' . $path);
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
        // Get stub content
        $stub = $this->files->get(__DIR__ . '/stubs/auth-controller.stub');
        
        // Calculate namespace
        $namespace = 'App\\Http\\Controllers';
        if (Str::contains($name, '/')) {
            $directoryPath = Str::beforeLast($name, '/');
            $namespace .= '\\' . str_replace('/', '\\', $directoryPath);
        }
        
        // Get class name
        $className = Str::afterLast($name, '/');
        
        // Replace placeholders
        $stub = str_replace('{{ namespace }}', $namespace, $stub);
        $stub = str_replace('{{ class }}', $className, $stub);
        
        return $stub;
    }
}