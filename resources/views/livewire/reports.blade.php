<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\On; 
new class extends Component {
    public $reports;

    #[On('echo:moderator-reports,.report-submitted')]
    public function loadReports()
    {
        $this->reports = Report::latest()->get();
    }

    public function mount()
    {
        $this->loadReports();
    }

    public function reject(Report $report) 
    {
        $report->delete(); 

        $this->loadReports(); // Refresh
    }
}; ?>

<div class="space-y-4">
    @foreach ($reports as $report)
        <div class="p-4 rounded-lg border border-neutral-600 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold">{{ $report->title }}</h2>
                <p class="text-neutral-400">{{ $report->description }}</p>
                <span class="text-xs text-gray-400">
                    Posted by: {{ $report->user->name ?? 'Anonymous' }}
                </span>
                <div class="flex gap-3 mt-3">
                    <flux:button size="sm">View</flux:button>
                    <flux:button size="sm" variant="primary">Accept</flux:button>
                    <flux:button size="sm" variant="filled" wire:click="reject({{ $report->id }})">Reject</flux:button>
                </div>
            </div>
            <flux:text>Reported {{ $report->created_at->diffForHumans() }}</flux:text>
        </div>
    @endforeach
</div>
