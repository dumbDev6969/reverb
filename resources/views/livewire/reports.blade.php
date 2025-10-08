<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    public $reports;
    
    public $editingReportId = null;
    public $editTitle = '';
    public $editDescription = '';

    protected function rules()
    {
        return [
            'editTitle' => 'required|string|max:255|min:10',
            'editDescription' => 'required|string|max:255|min:20',
        ];
    }

    protected $messages = [
        'editTitle.required' => 'Title is required.',
        'editTitle.min' => 'Title must be at least 10 characters.',
        'editDescription.required' => 'Description is required.',
        'editDescription.min' => 'Description must be at least 20 characters.',
    ];

    #[On('echo:moderator-reports,.report-submitted')]
    public function loadReports()
    {
        $this->reports = Report::latest()->get();
    }

    public function mount()
    {
        $this->loadReports();
    }

    public function editReport(Report $report)
    {
        $this->editingReportId = $report->id;
        $this->editTitle = $report->title;
        $this->editDescription = $report->description;
    }

    public function reject(Report $report)
    {
        $report->delete();
        $this->loadReports();
    }

    public function update(Report $report)
    {
        $this->validate();
        
        $report->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
        ]);
        
        $this->reset('editTitle', 'editDescription', 'editingReportId');
        $this->loadReports();
        
        $this->dispatch('close-modal', 'edit-report-' . $report->id);
    }

    
}; ?>

<div class="space-y-4">
    @foreach ($reports as $report)
        <div class="p-4 rounded-lg border border-neutral-600 flex justify-between items-center"
            wire:key="report-{{ $report->id }}">
            <div>
                <h2 class="text-lg font-bold">{{ $report->title }}</h2>
                <p class="text-neutral-400">{{ $report->description }}</p>
                <span class="text-xs text-gray-400">
                    Posted by: {{ $report->user->name ?? 'Anonymous' }}
                </span>
                <div class="flex gap-3 mt-3">
                    <flux:modal.trigger name="edit-report-{{ $report->id }}">
                        <flux:button size="sm" wire:click="editReport({{ $report->id }})">Edit</flux:button>
                    </flux:modal.trigger>
                    <flux:button size="sm" variant="primary" wire:click="accept({{ $report->id }})">Accept</flux:button>
                    <flux:button size="sm" variant="filled" wire:click="reject({{ $report->id }})">Reject</flux:button>
                </div>
            </div>
            <flux:text>Reported {{ $report->created_at->diffForHumans() }}</flux:text>
        </div>

        <flux:modal name="edit-report-{{ $report->id }}" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update report</flux:heading>
                    <flux:text class="mt-2">Make changes to the report details.</flux:text>
                </div>
                <form class="space-y-6" wire:submit.prevent="update({{ $report->id }})">
                    <flux:input 
                        wire:model.blur="editTitle" 
                        label="Title" 
                        placeholder="Enter report title"
                    />
                    @error('editTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <flux:textarea 
                        wire:model.blur="editDescription" 
                        label="Description" 
                        rows="4"
                        placeholder="Enter report description"
                    />
                    @error('editDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <div class="flex">
                        <flux:spacer />
                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endforeach
</div>