<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Auth events
        \App\Events\UserLoggedIn::class => [
            \App\Listeners\LogUserLogin::class,
            \App\Listeners\UpdateLastLogin::class,
            \App\Listeners\SyncUserDevices::class,
        ],
        \App\Events\UserLoggedOut::class => [
            \App\Listeners\LogUserLogout::class,
            \App\Listeners\CleanupUserSession::class,
        ],
        
        // Evaluation events
        \App\Events\EvaluationSubmitted::class => [
            \App\Listeners\SendApprovalNotification::class,
            \App\Listeners\LogEvaluationActivity::class,
            \App\Listeners\RunAIScoring::class,
        ],
        \App\Events\EvaluationApproved::class => [
            \App\Listeners\SendApprovalNotification::class,
            \App\Listeners\GenerateReport::class,
            \App\Listeners\UpdateComplianceStatus::class,
        ],
        \App\Events\EvaluationRejected::class => [
            \App\Listeners\SendRejectionNotification::class,
            \App\Listeners\LogEvaluationActivity::class,
        ],
        
        // Security events
        \App\Events\SecurityAlert::class => [
            \App\Listeners\SendSecurityAlert::class,
            \App\Listeners\LogSecurityEvent::class,
            \App\Listeners\NotifySecurityTeam::class,
        ],
        \App\Events\BruteForceDetected::class => [
            \App\Listeners\BlockIpAddress::class,
            \App\Listeners\NotifyAdmin::class,
        ],
        
        // Device events
        \App\Events\DeviceConnected::class => [
            \App\Listeners\SyncDeviceData::class,
            \App\Listeners\SendDeviceNotification::class,
            \App\Listeners\UpdateDeviceStatus::class,
        ],
        \App\Events\DeviceDisconnected::class => [
            \App\Listeners\CleanupDeviceSession::class,
            \App\Listeners\LogDeviceActivity::class,
        ],
        
        // Report events
        \App\Events\ReportGenerated::class => [
            \App\Listeners\SendReportNotification::class,
            \App\Listeners\StoreReportFile::class,
        ],
        
        // Sync events
        \App\Events\SyncCompleted::class => [
            \App\Listeners\UpdateSyncTimestamp::class,
            \App\Listeners\CleanupSyncQueue::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}