<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePublicLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_shared_header_footer_and_sidebar(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="open-sidebar"', false)
            ->assertSee('DISTINCTION', false)
            ->assertSee('All rights reserved.', false);
    }

    public function test_exams_index_renders_shared_header_footer_and_sidebar(): void
    {
        $this->get(route('exams.index'))
            ->assertOk()
            ->assertSee('id="open-sidebar"', false)
            ->assertSee('All rights reserved.', false);
    }

    public function test_books_list_renders_shared_header_footer_and_sidebar(): void
    {
        $this->get(route('home.books'))
            ->assertOk()
            ->assertSee('id="open-sidebar"', false)
            ->assertSee('All rights reserved.', false);
    }
}

