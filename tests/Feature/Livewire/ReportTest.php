<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;
    public function test_renders_successfully()
    {
        Livewire::test(Report::class)
            ->assertStatus(200);
    }

    public function test_component_exists()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('report.page')->assertSeeLivewire(Report::class);
    }
}
