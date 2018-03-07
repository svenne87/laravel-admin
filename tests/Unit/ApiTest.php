<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Http\Resources\UserResource;
use Laravel\Passport\Passport;

class ApiTest extends TestCase
{
    public function setUp()
    {
        // Rirst include all the normal setUp operations
        parent::setUp();

        // Now re-register all the roles and permissions
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Test the /me endpoint.
     *
     * @return void
     */
    public function testApiMe()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $userResource = new UserResource($user);
        
        $response = $this->json('GET', '/api/v1/me');
        $response
            ->assertStatus(200)
            ->assertExactJson(array("data" => $userResource->toArray()));
    }

    /**
     * Test the /users/{id} endpoint.
     *
     * @return void
     */
    public function testShowUser()
    {

        // Test if super admin
        // Should see Requested User here
        $user = User::findOrFail(1);
        $this->actingAs($user, 'api');

        $userRequested = factory(User::class)->create();
        $userResource = new UserResource($userRequested);
        
        $response = $this->json('GET', '/api/v1/users/' . $userRequested->id);
        $response
            ->assertStatus(200)
            ->assertExactJson(array("data" => $userResource->toArray()));


        // Test if normal User
        // Should not be allowed to access this resource
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $userResource = new UserResource($user);

        $userRequested = factory(User::class)->create();
      
        $response = $this->json('GET', '/api/v1/users/' . $userRequested->id);
        $response
            ->assertStatus(403)
            ->assertJsonFragment(array('errors'));
    }

    /**
     * Test the /users endpoint.
     *
     * @return void
     */
    public function testShowUsers()
    {
        // Test if super admin
        $user = User::findOrFail(1);
        $this->actingAs($user, 'api');
        $userResource = new UserResource($user);

        $response = $this->json('GET', '/api/v1/users');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(array("data" => array(array_keys($userResource->toArray()))));


        // Test if normal User
        // Should not be allowed to access this resource
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('GET', '/api/v1/users');
        $response
            ->assertStatus(403)
            ->assertJsonFragment(array('errors'));
    }

    /**
     * Test the /users endpoint (POST).
     *
     * @return void
     */
    public function testCreateUser()
    {
        // Test if super admin
        $user = User::findOrFail(1);
        $this->actingAs($user, 'api');

        $userCreate = factory(User::class)->make();
        $userCreate = $userCreate->toArray();
        $userCreate['password'] = 'secret';
        $userCreate['password_confirmation'] = 'secret';

        $response = $this->json('POST', '/api/v1/users', $userCreate);
        $response
            ->assertStatus(201)
            ->assertExactJson([
                'created' => true,
        ]);

        // Test if normal User
        // Should not be allowed to access this resource
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $userCreate = factory(User::class)->make();
        $userCreate = $userCreate->toArray();
        $userCreate['password'] = 'secret';

        $response = $this->json('POST', '/api/v1/users', $userCreate);
        $response 
            ->assertStatus(403)
            ->assertJsonFragment(array('errors'));
    }

        /**
     * Test the /users/ endpoint (DELETE).
     *
     * @return void
     */
    public function testRemoveUser()
    {
        // Test if super admin
        // Should see Requested User here
        $user = User::findOrFail(1);
        $this->actingAs($user, 'api');

        $userRemove = factory(User::class)->create();

        $response = $this->json('DELETE', '/api/v1/users/' . $userRemove->id);
        $response
            ->assertStatus(204);

        // Test if normal User
        // Should not be allowed to access this resource
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $userRemove = factory(User::class)->create();

        $response = $this->json('DELETE', '/api/v1/users/' . $userRemove->id);
        $response 
            ->assertStatus(403)
            ->assertJsonFragment(array('errors'));
    }

    /**
     * Test the /me endpoint. (UnAuthorized)
     *
     * @return void
     */
    public function testUnauthenticatedUser()
    {
        $this->json('GET', '/api/v1/me')->assertStatus(401);
        $this->json('GET', '/api/v1/users/1')->assertStatus(401);
        $this->json('GET', '/api/v1/users')->assertStatus(401);
        $this->json('POST', '/api/v1/users')->assertStatus(401);
        $this->json('DELETE', '/api/v1/users/1')->assertStatus(401);
    }
}
