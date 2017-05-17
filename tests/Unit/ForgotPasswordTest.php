<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Notifications\ResetPasswordNotification;

class ForgotPasswordTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    public function testPostToForgotPasswordActuallySendsEmail()
    {
        \Notification::fake();
        $user = \App\User::inRandomOrder()->first();

        $response = $this->post('password/email', ['email' => $user->email])
            ->assertStatus(200);
            
         \Notification::assertSentTo($user, ResetPasswordNotification::class);
    }
}
