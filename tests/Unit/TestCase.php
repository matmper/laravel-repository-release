<?php declare(strict_types=1);

namespace Tests;

use Database\Migrations\CreateUsersTable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $app = null;

    /**
     * Set up tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->migrate();
    }

    /**
     * Tear down tests
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->rollback();
        parent::tearDown();
    }

    /**
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $this->app = $app;

        $this->app->setBasePath(__DIR__ . '/..');

        Config::set('database.default', 'test');
        Config::set('database.connections.test', ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']);
        Config::set('cache.ttl', 5);
    }

    /**
     * Migrate database
     *
     * @return void
     */
    private function migrate(): void
    {
        $migrates = [new CreateUsersTable];

        foreach ($migrates as $migrate) {
            $migrate->up();
        }
    }

    /**
     * Rollback migrates
     *
     * @return void
     */
    private function rollback(): void
    {
        $migrates = [new CreateUsersTable];

        foreach ($migrates as $migrate) {
            $migrate->down();
        }
    }
}
