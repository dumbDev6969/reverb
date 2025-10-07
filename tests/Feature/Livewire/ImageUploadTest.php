<?php

namespace Tests\Feature\Livewire;

use App\Livewire\File\ImageUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;
    public function test_renders_successfully()
    {
        Livewire::test(ImageUpload::class)
            ->assertStatus(200);
    }

    public function test_component_exists() 
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('file.upload'))->assertSeeLivewire(ImageUpload::class);
    }
}
