<?php

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;
    public $action; // 'create', 'update', 'delete'

    public function __construct(Report $report, $action)
    {
        $this->report = $report;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('reports-channel');
    }

    public function broadcastWith()
    {
        return [
            'action' => $this->action,
            'report' => $this->report,
        ];
    }
}
