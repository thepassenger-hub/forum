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

    public function testUserCanBanForXDays()
    {
        $status = $this->user->status;
        $this->user->banFor(10);
        $newStatus = $this->user->status()->first();
        $this->assertNotEquals($status->status, $newStatus->status);
        $this->assertEquals('banned', $newStatus->status);
        $this->assertEquals(
            \Carbon\Carbon::parse($newStatus->until)->toDateString(),
            \Carbon\Carbon::now()->addDays(10)->toDateString()
        );
    }

    public function testUserCanEnableItsAccount()
    {
        $this->user->banFor(10);
        $this->assertEquals('banned', $this->user->status()->first()->status);
        $this->user->enable();
        $newStatus = $this->user->status()->first();
        $this->assertEquals('active', $newStatus->status);
        $this->assertNull($newStatus->until);
        
    }
}
