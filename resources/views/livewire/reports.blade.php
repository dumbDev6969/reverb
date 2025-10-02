<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\User;
new class extends Component {
    public $reports;
    public function mount()
    {
        $this->reports = Report::all();
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
                    <flux:button size="sm" variant="filled">Reject</flux:button>
                </div>
            </div>
            <flux:text>Reported {{ $report->created_at->diffForHumans() }}</flux:text>
        </div>
    @endforeach
</div>
