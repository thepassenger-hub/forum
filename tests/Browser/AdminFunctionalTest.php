<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use \App\User;

class AdminFunctionalTest extends DuskTestCase
{

    protected $admin;

    public function setUp()
    {
        
        parent::setUp();
        $this->admin = User::first();
        
         $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->loginAs($this->admin->id)
                    ->visit('/')
                    ->waitFor('.thread');
         });
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAdminCanDeleteAThread()
    {
        $this->browse(function (Browser $browser) {
            $firstTitle = $browser->text('.thread-title');
            $browser->click('.thread-title')
                    ->waitFor('.replies')
                    ->assertSeeIn('.thread', 'Remove')
                    ->press('Remove')
                    ->whenAvailable('.modal-container', function ($modal) {
                        $modal->assertSee('Are you sure you want to remove this thread?')
                        ->press('Yes');
                    })
                    ->waitFor('.thread')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home');
            $this->assertNotEquals($firstTitle, $browser->text('.thread-title'));
        });
    }

    public function testAdminCanDeleteAReply()
    {
        $this->browse(function (Browser $browser) {
            $browser->click('.thread-title')
                    ->waitFor('.replies')
                    ->assertSeeIn('.replies', 'Remove');
            $replyBody = $browser->text('.reply-body');
            $browser->click('.replies button.is-danger')
                    ->whenAvailable('.modal-container', function ($modal) {
                        $modal->assertSee('Are you sure you want to remove this reply?')
                        ->press('Yes');
                    })
                    ->waitFor('.thread')
                    ->assertDontSeeIn('.breadcrumb .is-active', 'Home');
            $this->assertNotEquals($replyBody, $browser->text('.reply-body'));
        });
    }

    public function testAdminCanBanUsers()
    {
        $user = User::where('isAdmin', 0)->first();
        $this->browse(function(Browser $browser) use ($user){
            $browser->clickLink('Admin Area')
                    ->waitFor('.user');
            $browser->assertSeeIn('.tabs .is-active', 'Users')
                    ->assertSee($user->username)
                    ->assertSeeIn('.status', 'Active')                    
                    ->assertSeeIn('.suspend-wrapper', 'Ban')
                    ->assertSeeIn('.suspend-wrapper', 'Suspend')
                    ->assertVisible('.suspend-wrapper input')
                    ->type('.suspension-time-input', '0')    
                    ->press('Suspend')
                    ->whenAvailable('.modal-container', function ($modal) use($user) {
                        $modal->assertSee("Are you sure you want to suspend {$user->username} for 0 days?")
                        ->press('Yes');
                    })
                    ->waitFor('.notification.is-danger')
                    ->assertSeeIn('.notification.is-danger', 'The days must be at least 1.')   
                    ->clear(".suspension-time-input")             
                    ->type('.suspension-time-input', '7')
                    ->press('Suspend')
                    ->whenAvailable('.modal-container', function ($modal) use($user) {
                        $modal->assertSee("Are you sure you want to suspend {$user->username} for 7 days?")
                        ->press('Yes');
                    })
                    ->waitFor('.user')
                    ->assertSeeIn('.status', 'Banned for 6 more days.')
                    ->press('Enable')
                    ->whenAvailable('.modal-container', function ($modal) use($user) {
                        $modal->assertSee("Are you sure you want to enable {$user->username}'s account?")
                        ->press('Yes');
                    })
                    ->waitFor('.user')
                    ->assertSeeIn('.status', 'Active')
                    ->press('Ban')
                    ->whenAvailable('.modal-container', function ($modal) {
                        $modal->assertSee('Are you sure you want to ban this user?')
                        ->press('Yes');
                    })
                    ->waitFor('.users')
                    ->assertSeeIn('.status', 'Banned')
                    ->assertSee('Enable');
        });
    }
}
