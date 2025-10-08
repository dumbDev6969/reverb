<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use App\Models\Report;
use App\Livewire\Report as DisplayReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Termwind\Components\Li;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;
    public function test_renders_successfully()
    {
        Livewire::test('report-form')->assertStatus(200);
    }

    public function test_can_set_title()
    {
        Livewire::test('report-form')->set('title', 'Any title')->assertSet('title', 'Any title');
    }

    public function test_owner_can_edit_report()
    {
        $user = User::factory()->create();
        $report = Report::factory()->create([
            'title' => 'Report to test',
            'description' => 'This is a report to test',
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test('reports', ['reportId' => $report->id])
            ->set('editTitle', 'New title lol lol lol')
            ->set('editDescription', 'New description lol lolololol')
            ->call('update', $report->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'title' => 'New title lol lol lol',
            'description' => 'New description lol lolololol',
        ]);
    }

    public function test_guest_cannot_edit_report()
    {
        $user = User::factory()->create();
        $guest = User::factory()->create();
        $report = Report::factory()->create([
            'title' => 'Report to test',
            'description' => 'This is a report to test',
            'user_id' => $user->id,
        ]);
        Livewire::actingAs($guest)
            ->test('reports', ['reportId' => $report->id])
            ->set('editTitle', 'Report now edited')
            ->set('editDescription', 'This is a report to test')
            ->call('update', $report->id)
            ->assertForbidden();

            $this->assertDatabaseHas('reports', [
                'id' => $report->id,
                'title' => 'Report to test',
                'description' => 'This is a report to test',
            ]);
    }

    public function test_display_all_reports()
    {
        $user = User::factory()->create();
        Report::factory()
            ->count(2)
            ->create([
                'title' => 'Report to test',
                'description' => 'This is a report to test',
                'user_id' => $user->id,
            ]);

        // Testing a volt file
        // volt file           // variable that holds reports
        Livewire::test('reports')->assertViewHas('reports', function ($c) {
            return count($c) == 2;
        });
    }
}
