<?php declare(strict_types=1);

namespace Tests;

use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

class UserRepositoryExceptionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * @test find or fail exception
     */
    public function test_find_or_fail_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $repository = new UserRepository;
        $repository->findOrFail(fake()->randomDigitNotNull(), ['id', 'name']);
    }

    /**
     * @test update empty data
     */
    public function test_update_empty_data_exception(): void
    {
        $this->expectException(\Matmper\Exceptions\EmptyArrayDataException::class);

        $user = User::factory()->create();

        $repository = new UserRepository;
        $repository->update($user->id, []);
    }
}
