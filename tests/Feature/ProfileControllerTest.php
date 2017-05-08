<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use \App\User;
class ProfileControllerTest extends TestCase
{

    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::inRandomOrder()->first();
        
    }

    public function testShowMethodReturnsCorrectProfileObject()
    {
        $profile = $this->user->profile()->with([
            'user' => function($query){
                        $query->withCount(['replies', 'threads']);
                        $query->with('replies.thread.channel');
                    }
            ])->first(); 

        $response = $this->get("profile/{$this->user->username}");
        $response->assertStatus(200);

        $this->assertEquals($profile->toJson(), $response->getContent());
    }

    public function testUpdateMethodActuallyUpdatesModel()
    {
        //If not logged in returns a redirect
        $response = $this->patch('/profile',[
            'name' => 'My new name',
            'bio' => 'My new bio',
            'location' => 'My new location'
        ])->assertStatus(302);

        //If logged in it updates current logged in user profile.
        $response = $this->actingAs($this->user)->patch('/profile',[
            'name' => 'My new name',
            'bio' => 'My new bio',
            'location' => 'My new location'
        ])->assertStatus(200);
        $profile = $this->user->profile()->first();
        $this->assertEquals($profile->name, 'My new name');
        $this->assertEquals($profile->bio, 'My new bio');
        $this->assertEquals($profile->location, 'My new location');
        
    }

    public function testUploadAvatarMethodStoresImageCorrectlyAndUpdatesDb()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($this->user)->json('POST', 'profile/avatar', [
            'avatar' => $file
        ]);
        
        // Assert the file was stored...        
        Storage::disk('local')->assertExists("public/avatars/{$file->hashName()}");

         $this->assertRegExp('/\.jpe?g$/', $this->user->profile()->first()->avatar);
    }
}
