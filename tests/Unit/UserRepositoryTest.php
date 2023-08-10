<?php declare(strict_types=1);

namespace Tests;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * @test find
     */
    public function test_find(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $response = $repository->find($user->id, ['id', 'name']);

        $this->assertNotEmpty($response);
        $this->assertEquals($user->id, $response->id);
        $this->assertEquals($user->name, $response->name);
        $this->assertNull($response->email);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test find
     */
    public function test_find_with_trashed(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create(['deleted_at' => now()]);

        $responseNull = $repository->find($user->id);
        $this->assertNull($responseNull);

        $response = $repository->withTrashed()->find($user->id);
        
        $this->assertNotEmpty($response);
        $this->assertEquals($user->id, $response->id);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test find or fail
     */
    public function test_find_or_fail(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $response = $repository->findOrFail($user->id, ['id', 'name']);

        $this->assertNotEmpty($response);
        $this->assertEquals($user->id, $response->id);
        $this->assertEquals($user->name, $response->name);
        $this->assertNull($response->email);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test first
     */
    public function test_first(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $response = $repository->first(['id' => $user->id], ['id', 'name']);

        $this->assertNotEmpty($response);
        $this->assertEquals($user->id, $response->id);
        $this->assertEquals($user->name, $response->name);
        $this->assertNull($response->email);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test first or fail
     */
    public function test_first_or_fail(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $response = $repository->firstOrFail(['id' => $user->id], ['id', 'name']);

        $this->assertNotEmpty($response);
        $this->assertEquals($user->id, $response->id);
        $this->assertEquals($user->name, $response->name);
        $this->assertNull($response->email);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test get
     */
    public function test_get(): void
    {
        $repository = new UserRepository;

        $users = User::factory(5)->create();
        $usersCount = $users->count();

        $response = $repository->get(['id >' => 1], ['id', 'name'], ['id' => 'DESC']);

        $this->assertNotEmpty($response);
        $this->assertCount(($usersCount - 1), $response->toArray());
        $this->assertEquals($users[$usersCount - 1]->id, $response[0]->id);
        $this->assertEquals($users[$usersCount - 1]->name, $response[0]->name);
        $this->assertNull($response[0]->email);
        $this->assertInstanceOf(Collection::class, $response);
    }

    /**
     * @test get
     */
    public function test_get_base_query(): void
    {
        $repository = new UserRepository;

        $users = User::factory(5)->create();
        $usersCount = $users->count();

        $response = $repository->getToBase(['id >' => 0], ['id', 'name'], ['id' => 'DESC'], true);

        $this->assertNotEmpty($response);
        $this->assertCount($usersCount, $response->toArray());
        $this->assertEquals($users[$usersCount - 1]->id, $response[0]->id);
        $this->assertEquals($users[$usersCount - 1]->name, $response[0]->name);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $response);
    }

    /**
     * @test paginate
     */
    public function test_paginate(): void
    {
        $repository = new UserRepository;

        $users = User::factory(25)->create();
        $usersCount = $users->count();

        $response = $repository->paginate(['id >' => 1], ['id', 'name'], ['id' => 'DESC'], $usersCount);

        $this->assertNotEmpty($response);
        $this->assertCount(($usersCount - 1), $response);
        $this->assertEquals($users[$usersCount - 1]->id, $response[0]->id);
        $this->assertEquals($users[$usersCount - 1]->name, $response[0]->name);
        $this->assertNull($response[0]->email);
    }

    /**
     * @test count
     */
    public function test_count(): void
    {
        $repository = new UserRepository;

        $users = User::factory(5)->create();
        $usersCount = $users->count();

        $response = $repository->count(['id >' => 1]);

        $this->assertIsInt($response);
        $this->assertEquals(($usersCount - 1), $response);
    }

    /**
     * @test create
     */
    public function test_create(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->make();

        $response = $repository->create($user->toArray());

        $this->assertNotEmpty($response);
        $this->assertEquals($user->name, $response->name);
        $this->assertEquals($user->email, $response->email);
        $this->assertEmpty($response->password);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test insert
     */
    public function test_insert(): void
    {
        $repository = new UserRepository;

        $users = User::factory(5)->make();

        $response = $repository->insert($users->toArray());

        $this->assertTrue($response);

        $allUsers = $repository->get([], ['name', 'email'], [])->toArray();
        
        foreach ($users as $i => $user) {
            $this->assertEquals($user->name, $allUsers[$i]['name']);
            $this->assertEquals($user->email, $allUsers[$i]['email']);
        }
    }

    /**
     * @test first or create
     */
    public function test_first_or_create(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->make();

        $validate = $repository->first(['email' => $user->email]);
        
        $responseOne = $repository->firstOrCreate(['email' => $user->email], ['name' => $user->name]);
        $responseTwo = $repository->firstOrCreate(['email' => $user->email], ['name' => $user->name]);
        
        $this->assertNull($validate);
        $this->assertNotEmpty($responseOne);
        $this->assertNotEmpty($responseTwo);
        $this->assertEquals($responseOne->id, $responseTwo->id);
        $this->assertEquals($user->name, $responseOne->name);
        $this->assertEquals($user->name, $responseTwo->name);
        $this->assertInstanceOf(User::class, $responseOne);
        $this->assertInstanceOf(User::class, $responseTwo);
    }

    /**
     * @test update or create
     */
    public function test_update_or_create(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->make();
        $newName = fake()->name();

        $validate = $repository->first(['email' => $user->email]);
        
        $responseOne = $repository->updateOrCreate(['email' => $user->email], ['name' => $user->name]);
        $responseTwo = $repository->updateOrCreate(['email' => $user->email], ['name' => $newName]);
        
        $this->assertNull($validate);
        $this->assertNotEmpty($responseOne);
        $this->assertNotEmpty($responseTwo);
        $this->assertEquals($responseOne->id, $responseTwo->id);
        $this->assertEquals($user->name, $responseOne->name);
        $this->assertEquals($newName, $responseTwo->name);
        $this->assertInstanceOf(User::class, $responseOne);
        $this->assertInstanceOf(User::class, $responseTwo);
    }

    /**
     * @test update or create
     */
    public function test_update(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();
        $newName = fake()->name();

        $response = $repository->update($user->id, ['name' => $newName]);
        
        $this->assertNotEmpty($response);
        $this->assertEquals($response->id, $user->id);
        $this->assertEquals($user->email, $response->email);
        $this->assertEquals($newName, $response->name);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test update collection
     */
    public function test_update_collection(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();
        $newName = fake()->name();

        $response = $repository->updateCollection($user, ['name' => $newName]);
        
        $this->assertNotEmpty($response);
        $this->assertEquals($response->id, $user->id);
        $this->assertEquals($user->email, $response->email);
        $this->assertEquals($newName, $response->name);
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test delete
     */
    public function test_delete(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $validate = $repository->find($user->id);
        $this->assertNotEmpty($validate);

        $repository->delete($user->id);

        $validate = $repository->find($user->id);
        $this->assertNull($validate);
    }

    /**
     * @test force delete
     */
    public function test_force_delete(): void
    {
        $repository = new UserRepository;

        $user = User::factory()->create();

        $validate = $repository->find($user->id);
        $this->assertNotEmpty($validate);

        $repository->forceDelete($user->id);

        $validate = $repository->withTrashed()->find($user->id);
        $this->assertNull($validate);
    }

    /**
     * @test with trashed and without trashed when config is false
     */
    public function test_with_and_without_trashed_config_false(): void
    {
        config(['repository.default.with_trashed' => false]);

        $repository = new UserRepository;

        $softUsers = User::factory(fake()->randomDigitNotNull())->create()->count();
        $deletedUsers = User::factory(fake()->randomDigitNotNull())->softDeleted()->create()->count();
        
        $this->assertEquals($repository->count([]), $softUsers);
        $this->assertEquals($repository->withTrashed()->count([]), ($softUsers + $deletedUsers));
        $this->assertEquals($repository->withoutTrashed()->count([]), $softUsers);
    }
    
    /**
     * @test with trashed and without trashed when config is true
     */
    public function test_with_and_without_trashed_config_true(): void
    {
        config(['repository.default.with_trashed' => true]);

        $repository = new UserRepository;

        $softUsers = User::factory(fake()->randomDigitNotNull())->create()->count();
        $deletedUsers = User::factory(fake()->randomDigitNotNull())->softDeleted()->create()->count();
        
        $this->assertEquals($repository->count([]), ($softUsers + $deletedUsers));
        $this->assertEquals($repository->withTrashed()->count([]), ($softUsers + $deletedUsers));
        $this->assertEquals($repository->withoutTrashed()->count([]), $softUsers);
    }
}
