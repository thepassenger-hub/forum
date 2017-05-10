<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FunctionalTest extends DuskTestCase
{
    
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create();
    }

    public function tearDown()
    {
        $this->user->delete();
        parent::tearDown();
        
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testHomePageHasFiltersThreadsLinksAndHero()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->waitFor('.thread')
                    ->assertTitle('Forum')
                    ->assertVisible('#search-bar')
                    ->assertSee('Login/Register')
                    ->assertSeeIn('.title', 'Forum Title')
                    ->assertVisible('.breadcrumb')
                    ->assertMissing('#new-thread-button')
                    ->assertSee('FILTERS')
                    ->assertSee('CHANNELS')
                    ->assertVisible('.pagination')
                    ->assertVisible('.thread')
                    ->assertSeeIn('footer', 'Forum by The passenger');
        });
    }

    public function testUserCanLogIn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')->waitFor('.thread')
                    ->clickLink('Login/Register')
                    ->waitForText('Log In')
                    ->assertSeeIn('.breadcrumb .is-active', 'sign-in')
                    ->type('#login-email', $this->user->email)
                    ->type('#login-password', 'secret')
                    ->press('Login')
                    ->waitFor('.thread')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home')
                    ->assertSeeIn('nav', $this->user->username)
                    ->assertSeeIn('nav', 'Logout');
        });
    }

    public function testGuestUserCanRegister()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread')
                    ->clickLink('Login/Register')
                    ->waitForText('Register New Account')
                    ->type('#register-username', 'johndoe')                    
                    ->type('#register-email', 'john@doe.com')
                    ->type('#register-password', 'secret')
                    ->type('#register-password-confirmation', 'secret')
                    ->press('Register')
                    ->waitFor('.thread')
                    ->assertSeeIn('.breadcrumb .is-active', 'Home')
                    ->assertSeeIn('nav', 'johndoe')
                    ->assertSeeIn('nav', 'Logout');

        });

        \App\User::where('username', 'johndoe')->delete();
    }

    public function testSearchBarIsWorking()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread')
                    ->type('#search-bar input', 'learn php')
                    ->press('SEARCH')
                    ->assertSeeIn('.breadcrumb', 'threads')
                    ->assertSeeIn('.thread-title', 'Learn PHP')
                    ->assertSeeIn('.thread-body', 'Lorem ipsum dolor');
                    
        });
    }

    public function testFiltersAreFilteringThreads()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread')
                    ->assertDontSeeIn('aside', 'My Threads')
                    ->assertDontSeeIn('aside', 'My Partecipation');
            $repliesBefore = $browser->text('.reply-count');
            $browser->clickLink('Popular this week')
                    ->pause(500)
                    ->assertSeeIn('aside .is-active', 'Popular this week');
            $repliesAfter = $browser->text('.reply-count');
            $this->assertGreaterThanOrEqual($repliesBefore, $repliesAfter);

            $browser->clickLink('Popular All Time')
                    ->pause(500)            
                    ->assertSeeIn('aside .is-active', 'Popular All Time');
            $repliesAfter = $browser->text('.reply-count');
            $this->assertGreaterThanOrEqual($repliesBefore, $repliesAfter);

            $browser->clickLink('All Threads')
                    ->pause(500)            
                    ->assertSeeIn('aside .is-active', 'All Threads');
            $repliesAfter = $browser->text('.reply-count');
            $this->assertEquals($repliesBefore, $repliesAfter);
        });
    }

    public function testChannelsLinkFilterCorrectly()
    {
         $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread')
                    ->clickLink('PHP')
                    ->pause(500)
                    ->assertSeeIn('h3.title', 'PHP')
                    ->assertSeeIn('.breadcrumb .is-active', 'php')
                    ->assertSeeIn('.channel-link', 'PHP');
         });
    }

    public function testPaginationLinksWorkCorrectly()
    {
         $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread')
                    ->assertSeeIn('.pagination-link.is-current', '1')
                    ->assertSeeIn('.pagination .is-disabled', 'Previous');
            $thread1 = $browser->text('.thread-title');
            $browser->clickLink('Next')
                    ->assertSeeIn('.pagination-link.is-current', '2')
                    ->assertMissing('.pagination .is-disabled');
            $thread2 = $browser->text('.thread-title');
            $this->assertNotEquals($thread1, $thread2);
            $browser->clickLink('5')            
                    ->assertSeeIn('.pagination-link.is-current', '5')
                    ->assertMissing('.pagination .is-disabled');
            $thread5 = $browser->text('.thread-title');
            $this->assertNotEquals($thread2, $thread5);
            $browser->click('#paginate-next-group')  
                    ->assertSeeIn('.pagination-link.is-current', '8')
                    ->assertMissing('.pagination .is-disabled');
            $thread8 = $browser->text('.thread-title');                           
            $this->assertNotEquals($thread8, $thread5);
            $browser->clickLink('...')  
                    ->assertSeeIn('.pagination-link.is-current', '5')
                    ->assertMissing('.pagination .is-disabled');
                    
            $this->assertEquals($thread5, $browser->text('.thread-title'));
            $browser->clickLink('11')  
                    ->assertSeeIn('.pagination-link.is-current', '11')
                    ->assertSeeIn('.pagination .is-disabled', 'Next');
            $thread11 = $browser->text('.thread-title');                           
            $this->assertNotEquals($thread8, $thread11);
            $browser->clickLink('Previous')  
                    ->assertSeeIn('.pagination-link.is-current', '10')
                    ->assertMissing('.pagination .is-disabled');
            $this->assertNotEquals($thread11, $browser->text('.thread-title'));
         });
    }
    public function testBreadcrumbWorksCorrectly()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/#/php/learn-php-in-3-steps')->waitFor('.thread')
                ->assertSeeIn('.thread-title', 'Learn PHP in 3 steps.')
                ->assertSeeIn('.breadcrumb .is-active', 'learn-php-in-3-steps')
                ->assertSeeIn('.breadcrumb li:nth-child(2)', 'php')
                ->assertSeeIn('.breadcrumb li', 'Home')
                ->clickLink('php')
                ->pause(200)                
                ->assertSeeIn('h3.title', 'PHP')
                ->assertSeeIn('.breadcrumb .is-active', 'php')
                ->assertSeeIn('.breadcrumb li', 'Home')
                ->clickLink('Home')
                ->assertSeeIn('.breadcrumb .is-active', 'Home')
                ->assertMissing('.replies');
        });
    }

    public function testCanVisitUserProfile()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')->waitFor('.thread');
            $userName = $browser->text('.created-by a');
            $browser->clickLink($userName)
                    ->waitFor('.view-profile')
                    ->assertVisible('#avatar')
                    ->assertVisible('#profile-infos')
                    ->assertVisible('#profile-bio')
                    ->assertSeeIn('.view-profile', 'Reply on')
                    ->assertMissing('.tabs')
                    ->assertDontSee('Edit');

            $this->assertEquals($userName, $browser->text('#profile-username'));
        });            
    }

    public function testGuestCanVisitThreadButCantReply()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/')
                    ->waitFor('.thread');
            $title = $browser->text('.thread-title a');
            $browser->click('.thread-title a')
                    ->pause(200)
                    ->assertVisible('.thread')
                    ->assertVisible('.replies')
                    ->assertVisible('.breadcrumb li:nth-child(3)')
                    ->assertMissing('#new-reply-form')
                    ->assertMissing('.reply-edit')
                    ->assertMissing('.reply-delete');
        });

    }
}
