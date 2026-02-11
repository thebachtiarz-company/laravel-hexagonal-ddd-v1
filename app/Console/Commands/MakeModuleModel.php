<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-model {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Model inside a specific module';

    /**
     * Module target
     */
    protected string $moduleName;

    /**
     * Class name
     */
    protected string $className;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->moduleName = Str::studly($this->argument('module'));
        $this->className = Str::studly($this->argument('name'));

        $modulePath = app_path(sprintf('Modules/%s', $this->moduleName));
        if (! File::exists($modulePath)) {
            $this->error(sprintf('Module %s does not exist!', $this->moduleName));

            return;
        }

        // Create Model Eloquent ( Infrastructure/Persistence/Eloquent/Models )
        $this->generateFile('Infrastructure/Persistence/Eloquent/Models/{{ $name }}Model.php', 'eloquent-model');

        // Create Factory ( Infrastructure/Persistence/Eloquent/Factories )
        $this->generateFile('Infrastructure/Persistence/Eloquent/Factories/{{ $name }}Factory.php', 'eloquent-factory');

        // Create Migration ( Infrastructure/Persistence/Migrations )
        $this->generateFile(sprintf('Infrastructure/Persistence/Migrations/%s_create_%s_table.php', now()->format('Y_m_d_His'), Str::snake(Str::plural($this->className))), 'migration.create');

        // Create Domain Entity ( Domain/Entities )
        $this->generateFile('Domain/Entities/{{ $name }}Entity.php', 'entity-model');

        // Create Port ( Domain/Ports )
        $this->generateFile('Domain/Ports/{{ $name }}RepositoryInterface.php', 'port-repository');

        // Create Model Eloquent Repository ( Infrastructure/Persistence/Eloquent/Repositories )
        $this->generateFile('Infrastructure/Persistence/Eloquent/Repositories/{{ $name }}Repository.php', 'eloquent.repository');

        $this->info(sprintf('Successfully generated Hexagonal DDD boilerplate for %s in %s!', $this->className, $this->moduleName));
        $this->newLine();
        $this->info(str_replace(
            ['{{ $name }}', '{{ $serviceProvider }}'],
            [$this->className, sprintf('app/Modules/%s/Application/Providers/ServiceProvider.php', $this->moduleName)],
            'Make sure to do bind the interface \'{{ $name }}RepositoryInterface\' and repository \'{{ $name }}Repository\' into {{ $serviceProvider }}.',
        ));
    }

    protected function generateFile(string $targetPath, string $stubName): void
    {
        $path = app_path(sprintf('Modules/%s/%s', $this->moduleName, str_replace('{{ $name }}', $this->className, $targetPath)));
        File::ensureDirectoryExists(dirname($path));

        $stub = File::get(base_path("stubs/hexagonal/{$stubName}.stub"));
        $content = str_replace(
            ['{{ $module }}', '{{ $name }}', '{{ $tableName }}'],
            [$this->moduleName, $this->className, Str::snake(Str::plural($this->className))],
            $stub,
        );

        File::put($path, $content);
    }
}
