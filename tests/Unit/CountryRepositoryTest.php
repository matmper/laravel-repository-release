<?php declare(strict_types=1);

namespace Tests;

use App\Models\Country;
use App\Repositories\CountryRepository;
use Tests\TestCase;

class CountryRepositoryTest extends TestCase
{
    /**
     * @test find
     */
    public function test_find(): void
    {
        $repository = new CountryRepository;

        $country = Country::factory()->create();

        $response = $repository->find($country->id);
        $this->assertInstanceOf(Country::class, $response);
    }

    /**
     * @test find
     */
    public function test_find_with_trashed(): void
    {
        $repository = new CountryRepository;

        $country = Country::factory()->create();

        $response = $repository->find($country->id);
        $this->assertInstanceOf(Country::class, $response);

        $response = $repository->withTrashed()->find($country->id);
        $this->assertInstanceOf(Country::class, $response);
    }
    
    /**
     * @test delete
     */
    public function test_delete(): void
    {
        $repository = new CountryRepository;

        $country = Country::factory()->create();

        $validate = $repository->find($country->id);
        $this->assertInstanceOf(Country::class, $validate);

        $repository->delete($country->id);

        $validate = $repository->withTrashed()->find($country->id);
        $this->assertNull($validate);
    }

    /**
     * @test force delete
     */
    public function test_force_delete(): void
    {
        $repository = new CountryRepository;

        $country = Country::factory()->create();

        $validate = $repository->find($country->id);
        $this->assertInstanceOf(Country::class, $validate);

        $repository->forceDelete($country->id);

        $validate = $repository->withTrashed()->find($country->id);
        $this->assertNull($validate);
    }
}
