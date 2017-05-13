<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use \App\User;

class AdminFunctionalTest extends DuskTestCase
{

    public function setUp()
    {
        parent::setUp();
         $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->loginAs(User::first()->id)
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
}
