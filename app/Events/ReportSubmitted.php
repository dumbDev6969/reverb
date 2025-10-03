<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;

class ReportSubmitted implements ShouldBroadcast
{   
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function broadcastOn()
    {
        return new Channel('moderator-reports'); // public channel
    }

    public function broadcastAs()
    {
        return 'report-submitted';
    }
}
