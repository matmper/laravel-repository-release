<?php declare(strict_types=1);

namespace Tests;

use Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * @test user repository file exists
     */
    public function test_repository_exists(): void
    {
        $file = app_path('Repositories/UserRepository.php');

        $this->assertTrue(file_exists($file));
    }

    /**
     * @test config file exists
     */
    public function test_config_exists(): void
    {
        $file = __DIR__ . '/../../config/repository.php';

        $this->assertTrue(file_exists($file));
    }
}
