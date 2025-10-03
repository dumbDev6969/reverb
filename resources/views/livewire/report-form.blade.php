<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use App\Events\ReportSubmitted;

new class extends Component {
    #[Validate]
    public $title = '';

    public $description = '';

    public $showToast = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|max:255',
        ];
    }

    public function report()
    {
        $validated = $this->validate();

        try {
            $report = Report::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'user_id' => Auth::user()->id,
            ]);

            ReportSubmitted::dispatch($report);

            $this->reset('title', 'description');
            $this->showToast = true;
            $this->dispatchBrowserEvent('toast-hide', ['timeout' => 3000]);
             session()->flash('success', 'Report has been sent successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}; ?>

<div class="space-y-4 p-3 max-w-5xl mx-auto h-200 flex justify-center  items-center">
    <form method="POST" wire:submit.prevent="report" class="flex flex-col gap-4 w-full ">
        <flux:input type="text" label="{{ __('Title') }}" wire:model.blur="title" placeholder="Title" />

        <flux:textarea label="{{ __('Description') }}" wire:model.blur="description"
            placeholder="Tell us what happened" />

        <flux:button variant="primary" type="submit" class="w-full" data-test="report-button">
            {{ __('Report') }}
        </flux:button>
    </form>
    <div class="fixed bottom-4 right-4 z-50 space-y-2">
        @if ($showToast)
            <div id="toast-success"
                class=" ms-auto flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('success') }}</div>
                <button type="button" wire:click="$set('showToast', false)"
                    class="-mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close" aria-controls="toast-success">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>
