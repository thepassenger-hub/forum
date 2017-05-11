<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\Thread;

class AuthFunctionalTest extends DuskTestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();
        
        $this->user = factory(\App\User::class)->create();
        $this->browse(function (Browser $browser) {
            $browser->visit('/#/sign-in')
                    ->waitForText('Log In')
                    ->type('#login-email', $this->user->email)
                    ->type('#login-password', 'secret')
                    ->press('Login')
                    ->waitFor('.thread');
        });
    }

    public function tearDown()
    {
        $this->user->delete();
        parent::tearDown();
        
    }

    public function testUserCanCreateNewThreadModifyItAndDeleteIt()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertVisible('#new-thread-button')
                    ->clickLink('Create new Thread')
                    ->pause(200)
                    ->assertSeeIn('.breadcrumb .is-active', 'new-thread')
                    ->type('title', 'Thread with laravel Dusk')
                    ->select('channel', 'php')
                    ->type('body', 'Lorem ipsum dolor sit amet')
                    ->press('Submit')
                    ->waitFor('.thread')
                    ->assertSeeIn('.breadcrumb .is-active', 'thread-with-laravel-dusk')
                    ->assertVisible('.thread-modifiers')
                    ->click('.thread-edit')
                    ->waitFor('.edit-thread-form')
                    ->clear('thread-message')
                    ->type('thread-message', 'Editing the thread message.')
                    ->press('Update your reply')
                    ->pause(200)
                    ->assertSeeIn('.thread-body', 'Editing the thread message.')
                    ->click('.thread-delete')
                    ->whenAvailable('.modal-container', function ($modal) {
                        $modal->assertSee('Are you sure you want to delete your thread?')
                        ->press('Yes');
                    })
                    ->pause(200)
                    ->assertSeeIn('.breadcrumb .is-active', 'Home');
        });
    }

    public function testUserCanAddReplyToThreadAndEditAndDeleteIt()
    {
        $this->browse(function (Browser $browser) {
            $browser->pause(200)->click('.thread-title')
                    ->waitFor('.replies')
                    ->assertVisible('#new-reply-form')
                    ->type('body', 'Adding reply with laravel Dusk')
                    ->pause(200)
                    ->press('Submit')
                    ->pause(1000)
                    ->assertSee('Adding reply with laravel Dusk')
                    ->assertVisible('.reply-modifiers')
                    ->click('.reply-edit')
                    ->assertVisible('.edit-reply-form')
                    ->clear('reply-message')
                    ->type('reply-message', 'Editing the reply message.')
                    ->press('Update your reply')
                    ->pause(200)
                    ->assertSee('Editing the reply message.')
                    ->click('.reply-delete')
                    ->whenAvailable('.modal-container', function ($modal) {
                        $modal->assertSee('Are you sure you want to delete your reply?')
                        ->press('Yes');
                    })
                    ->pause(1000)
                    ->assertVisible('#new-reply-form')
                    ->assertMissing('.reply-modifiers')
                    ->assertDontSee('Editing the reply message.');
        });
    }

    public function testCanModifyMyProfile()
    {
        //  $this->browse(function (Browser $browser) {
        //     $browser->pause(200)->click('.thread-title')
    }
}
