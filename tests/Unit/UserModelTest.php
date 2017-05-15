<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\User;

class UserModelTest extends TestCase
{

    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::first();
    }
    public function testUserModelCanRetrieveItsStatus()
    {
        $status = $this->user->status;
        $this->assertEquals(\App\Status::where('user_id', 1)->first(), $status);
    }
}
