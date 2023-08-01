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

namespace App\Repositories;

use Matmper\\Repositories\\BaseRepository;
use App\Models\\{$modelName};

final class {$modelName}Repository extends BaseRepository
{
    /**
     * @var {$modelName}
     */
    protected \$model = {$modelName}::class;
}
";
    }
}
