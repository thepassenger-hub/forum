<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FunctionalTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testHomePageHasFiltersThreadsLinksAndHero()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertTitle('Forum')
                    ->assertVisible('#search-bar')
                    ->assertSee('Login/Register')
                    ->assertSeeIn('.title', 'Forum Title')
                    ->assertVisible('.breadcrumb')
                    ->assertSee('FILTERS')
                    ->assertSee('CHANNELS')
                    ->assertVisible('.pagination')
                    ->assertVisible('.thread')
                    ->assertSeeIn('footer', 'Forum by The passenger');

        });
    }
}
