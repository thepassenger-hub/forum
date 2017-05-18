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
        $this->user = \App\User::inRandomOrder()->first();
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->loginAs($this->user->id)
                    ->visit('/')
                    ->waitFor('.thread');
        });
    }

    public function testUserCanCreateNewThreadModifyItAndDeleteIt()
    {
        $this->browse(function (Browser $browser) {
            $browser->assertDontSee('Admin Area')
                    ->assertVisible('#new-thread-button')
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
                    ->assertMissing('button.is-danger')                    
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
                    ->assertDontSee('Editing the reply message.');
        });
    }

    public function testCanModifyMyProfile()
    {
         $this->browse(function (Browser $browser) {
            $browser->pause(200)->clickLink($this->user->username)
                    ->waitFor('.view-profile')
                    ->assertSeeIn('.breadcrumb .is-active', "@{$this->user->username}")
                    ->assertVisible('.tabs')
                    ->assertSeeIn('.tabs .is-active', 'View')
                    ->clickLink('Edit')
                    ->waitFor('.edit-profile')
                    ->assertSeeIn('.tabs .is-active', 'Edit')
                    ->attach('#add-new-avatar', base_path() .'/public/images/drawing.svg')
                    ->press('Upload Avatar')
                    ->assertMissing('.notification.is-danger')
                    ->waitFor('.view-profile')
                    ->clickLink('Edit')
                    ->waitFor('.edit-profile')
                    ->type('#edit-password-current', 'secret')
                    ->type('#edit-password-new', 'mynewpassword')
                    ->type('#edit-password-confirmation', 'wrongconfirmation')
                    ->press('Change Password')
                    ->pause(1000)
                    ->assertVisible('.notification.is-danger')
                    ->click('.notification.is-danger button')
                    ->clear('#edit-password-confirmation')
                    ->type('#edit-password-confirmation', 'mynewpassword')
                    ->press('Change Password')                    
                    ->pause(1000)
                    ->assertVisible('.notification.is-success')
                    ->type('#profile-name-input', 'John')
                    ->type('#profile-bio-textarea', 'John doe using laravel dusk')
                    ->type('#profile-location-input', 'JohnDoeLand')
                    ->press('Save changes')
                    ->waitFor('.view-profile');
         });
    }

    public function testAuthUserCantVisitAdminArea()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')
                    ->waitFor('.thread')
                    ->assertDontSee('Admin Area')
                    ->visit('/#/admin/users')
                    ->pause(200)
                    ->assertMissing('.user')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home')                    
                    ->visit('/#/admin/threads')
                    ->pause(200)
                    ->assertMissing('button.is-danger')
                    ->assertDontSee('Delete')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home')
                    ->visit('/#/admin/replies')
                    ->pause(200)
                    ->assertMissing('.replies')
                    ->assertDontSee('Delete')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home');
        });
    }

    public function testBannedUserCantCreateThread()
    {   
         $this->browse(function (Browser $browser) {
            $browser->assertVisible('#new-thread-button')
                    ->clickLink('Create new Thread')
                    ->pause(200)
                    ->assertSeeIn('.breadcrumb .is-active', 'new-thread')
                    ->type('title', 'Thread with laravel Dusk')
                    ->select('channel', 'php')
                    ->type('body', 'Lorem ipsum dolor sit amet');
            $this->user->banForDays(10);
            $browser->press('Submit')
                    ->waitFor('.notification.is-danger')
                    ->pause(200)
                    ->assertSeeIn('.notification.is-danger', 'You are banned for 10 more days.');
        });

        $this->user->enable();
    }

    public function testBannedUserCantReply()
    {   
         $this->browse(function (Browser $browser) {
            $browser->click('.thread-title')
                    ->waitFor('.replies')
                    ->type('body', 'Adding reply with laravel Dusk');
            $this->user->banForDays(10);
            $browser->press('Submit')
                    ->waitFor('.notification.is-danger')
                    ->pause(200)
                    ->assertSeeIn('.notification.is-danger', 'You are banned for 10 more days.');
        });

        $this->user->enable();
    }
}
