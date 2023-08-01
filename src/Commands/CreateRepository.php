<?php

namespace Matmper\Commands;

use Illuminate\Console\Command;
use Matmper\Commands\Support\CreateRepositoryFile;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:create {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository file';

    /**
     * @var string
     */
    private $path;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->setPath();

        $argument = $this->argument('model');
        $models = $argument === 'all' ? array_slice(scandir(app_path('Models')), 2) : [$argument];

        foreach ($models as $model) {
            $this->createFiles($model);
            $this->newLine(1);
        }

        $this->alert("Well Done!");
    }

    /**
     * Create a single repositoiry file
     *
     * @param string $model
     * @return void
     */
    private function createFiles(string $model): void
    {
        $model = str_replace(['/', '\\', '.php', 'php'], '', $model);
        $file = $this->getRepositoryData($model);
        

        $this->comment("Creating {$file->fileName}");

        if (!file_exists(app_path("Models/$model.php"))) {
            $this->error("{$model} does not exist in ./app/Models folder");
            return;
        }

        $pathToFile = $file->filePath . '/' . $file->fileName;

        if (file_exists($pathToFile)) {
            $this->error("{$file->fileName} file already exists");
            return;
        }

        $fileHandle = fopen($pathToFile, "a+");

        if (empty($fileHandle)) {
            $this->error("{$file->fileName} cannot be created successfully");
            return;
        }

        fwrite($fileHandle, $file->content);
        fclose($fileHandle);

        $this->info("{$file->fileName} created successfully");
    }

    /**
     * Create array data to create a new repository file
     *
     * @param string $model
     * @return object
     */
    private function getRepositoryData(string $model): object
    {
        return (object) [
            'fileName' => "{$model}Repository.php",
            'filePath' => $this->path,
            'content' => (new CreateRepositoryFile())($model),
        ];
    }

    /**
     * Check if repositories path exists, create and set it
     *
     * @return void
     */
    private function setPath(): void
    {
        $this->path = app_path('Repositories');

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755);
        }
    }
}
