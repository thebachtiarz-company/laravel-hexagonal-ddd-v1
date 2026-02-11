<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize a new Hexagonal Module structure';

    /**
     * Module target
     */
    protected string $moduleName;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->moduleName = Str::studly($this->argument('name'));
        $basePath = app_path(sprintf('Modules/%s', $this->moduleName));

        if (File::exists($basePath)) {
            $this->error(sprintf('Module %s already exists!', $this->moduleName));

            return;
        }

        $directories = [
            'Application/DTO',
            'Application/Providers',
            'Application/UseCases',
            'Domain/Entities',
            'Domain/Events',
            'Domain/Exceptions',
            'Domain/Ports',
            'Domain/ValueObjects',
            'Infrastructure/Config',
            'Infrastructure/ExternalAPI',
            'Infrastructure/ExternalServices',
            'Infrastructure/Helper',
            'Infrastructure/Listeners',
            'Infrastructure/Persistence/Eloquent/Factories',
            'Infrastructure/Persistence/Eloquent/Models',
            'Infrastructure/Persistence/Eloquent/Repositories',
            'Infrastructure/Persistence/Migrations',
            'Tests/Browser',
            'Tests/Feature',
            'Tests/Unit',
            'UI/Controllers',
            'UI/Filament',
            'UI/Middleware',
            'UI/Requests',
            'UI/Routes',
        ];

        foreach ($directories as $directory) {
            File::makeDirectory("{$basePath}/{$directory}", 0755, true);
        }

        // Create ServiceProvider file
        $this->generateFile('Application/Providers/ServiceProvider.php', 'base-provider');

        // Create route api file
        $this->generateFile('UI/Routes/api.php', 'route-api');

        $this->info(sprintf('Module %s structure initialized successfully!', $this->moduleName));
    }

    protected function generateFile(string $targetPath, string $stubName): void
    {
        $path = app_path(sprintf('Modules/%s/%s', $this->moduleName, $targetPath));
        File::ensureDirectoryExists(dirname($path));

        $stub = File::get(base_path("stubs/laravel/{$stubName}.stub"));
        $content = str_replace(
            ['{{ $module }}', '{{ $nameLower }}'],
            [$this->moduleName, Str::lower($this->moduleName)],
            $stub,
        );

        File::put($path, $content);
    }
}
