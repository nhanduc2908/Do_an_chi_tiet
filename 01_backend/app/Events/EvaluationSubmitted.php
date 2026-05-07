<?php

namespace App\Events;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluationSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Evaluation $evaluation;
    public User $submittedBy;

    public function __construct(Evaluation $evaluation, User $submittedBy)
    {
        $this->evaluation = $evaluation;
        $this->submittedBy = $submittedBy;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("company.{$this->evaluation->company_id}"),
            new PrivateChannel("user.{$this->submittedBy->id}"),
        ];

        $approvers = User::whereIn('role', ['admin', 'manager', 'ciso'])->get();
        foreach ($approvers as $approver) {
            $channels[] = new PrivateChannel("user.{$approver->id}");
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'evaluation.submitted';
    }

    public function broadcastWith(): array
    {
        return [
            'evaluation_id' => $this->evaluation->id,
            'evaluation_title' => $this->evaluation->title,
            'percentage' => $this->evaluation->percentage,
            'submitted_by' => $this->submittedBy->name,
            'submitted_by_role' => $this->submittedBy->role,
            'submitted_at' => now()->toIso8601String(),
            'approval_url' => route('evaluations.show', $this->evaluation->id),
        ];
    }
}