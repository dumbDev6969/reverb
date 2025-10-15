<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report as ShowReport;
use Livewire\Attributes\Locked;
class Report extends Component
{
    #[Locked]
    public $report;
    public function mount (ShowReport $report)
    {
        $this->report = $report;
    }
    public function render()
    {
        return view('livewire.report', ['report', $this->report]);
    }
}
