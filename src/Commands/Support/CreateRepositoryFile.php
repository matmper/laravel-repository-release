<?php

namespace Matmper\Commands\Support;

class CreateRepositoryFile
{
    public function __invoke(string $modelName): string
    {
        return "<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\\Repositories;

use Matmper\\Repositories\\BaseRepository;

final class {$modelName}Repository extends BaseRepository
{
    /**
     * @var \\App\\Models\\{$modelName};
     */
    protected \$model;

    public function __construct()
    {
        \$this->model = new \\App\\Models\\{$modelName}();
        parent::__construct();
    }
}
";
    }
}
