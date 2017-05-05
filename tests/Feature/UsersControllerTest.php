<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\User;
class UsersControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::first();
    }

    public function testValidationWorksAsIntended()
    {
        $password_before = $this->user->password;
        $this->actingAs($this->user)->patch('user/password',[
            'oldPassword' => 'short',
            'password' => 'whateverpasswordyouwant',
            'password_confirmation' => 'whateverpasswordyouwant'
        ])->assertStatus(302);
        $password_after = User::find($this->user->id)->password;
        
        $this->assertEquals($password_before, $password_after);
    }

    public function testOldPasswordDontMatchReturnsError()
    {
        $password_before = $this->user->password;
        
        $this->actingAs($this->user)->patch('user/password',[
            'oldPassword' => 'thewrongpassword',
            'password' => 'whateverpasswordyouwant',
            'password_confirmation' => 'whateverpasswordyouwant'
        ])->assertStatus(403);

        $password_after = User::find($this->user->id)->password;
        $this->assertEquals($password_before, $password_after);
        
    }

    public function testCorrectInputUpdateUsersPassword()
    {
        $password_before = $this->user->password;
        
        $this->actingAs($this->user)->patch('user/password',[
            'oldPassword' => 'password',
            'password' => 'whateverpasswordyouwant',
            'password_confirmation' => 'whateverpasswordyouwant'
        ])->assertStatus(200);

        $password_after = User::first()->password;
        
        $this->assertNotEquals($password_before, $password_after);
    }
}
