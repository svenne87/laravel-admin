<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Post;
use App\Http\Resources\PostResource;

class PostsTest extends TestCase
{
    public function setUp()
    {
        // Rirst include all the normal setUp operations
        parent::setUp();

        // Now re-register all the roles and permissions
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Test the /{slug} endpoint.
     *
     * @return void
     */
    public function testViewPage()
    {
        
    }

}