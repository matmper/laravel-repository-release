<?php declare(strict_types=1);

namespace Tests;

use App\Models\Country;
use App\Repositories\CountryRepository;
use Tests\TestCase;

class CountryRepositoryExceptionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * @test restore exception
     */
    public function test_restore_exception(): void
    {
        $this->expectException(\Matmper\Exceptions\OnlyModelsWithSoftDeleteException::class);

        $country = Country::factory()->create();

        $repository = new CountryRepository;
        $repository->delete($country->id);

        $repository = new CountryRepository;
        $repository->withTrashed()->restore(fake()->randomDigitNotNull(), ['id', 'name']);
    }
}
