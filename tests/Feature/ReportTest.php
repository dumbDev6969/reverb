<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
class ReportTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $user = User::factory()->create();
        $reportData = [
            'title' => 'Test Report',
            'description' => 'This is a test report.',
        ];

        $response = $this->actingAs($user)->post(route('report.page'), $reportData);

        $response->assertRedirect();
        $response->assetSessionHas('success', 'Report submitted successfully.');

        $response->assertStatus(200);
    }

     
    public function guest_cannot_create_report()
    {
        $reportData = [
            'title' => 'Test Report',
            'description' => 'Just testing access control.',
        ];

        
        $response = $this->post(route('report.page'), $reportData);

       
        $response->assertRedirect(route('login'));
    }

    
    public function report_requires_title_and_description()
    {
        $user = User::factory()->create();

      
        $response = $this->actingAs($user)->get(route('report.page'), []);

       
        $response->assertSessionHasErrors(['title', 'description']);
    }
}
