<?php

namespace App\Livewire\File;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Models\Upload\ImgUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
class ImageUpload extends Component
{
    use WithFileUploads;

    #[Validate]
    public $image;

    public $uploadKey;

    protected $rules = [
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function mount() 
    {
        $this->uploadKey = rand();
    }
    public function save () 
    {
        $this->validate();
        $path = Storage::putFile('private', $this->image);

        try {
            ImgUpload::create([
                'image' => $path,
                'user_id' => Auth::user()->id
            ]);

            $this->image = null;
            $this->reset('image');
            $this->uploadKey = rand();

            session()->flash('success', 'Image has been uploaded successfully.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'An error occurred while uploading the image.');
        }
        

    }
    public function render()
    {
        return view('livewire.file.image-upload');
    }
}
