<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Setup Commands
        Commands\Setup\AppInstall::class,
        Commands\Setup\CreateAdminUser::class,
        Commands\Setup\GenerateApiDocs::class,
        Commands\Setup\SeedTestData::class,
        Commands\Setup\WarmupCache::class,
        
        // Backup Commands
        Commands\Backup\BackupDatabase::class,
        Commands\Backup\RestoreDatabase::class,
        Commands\Backup\BackupS3Upload::class,
        Commands\Backup\CleanupOldBackups::class,
        
        // Maintenance Commands
        Commands\Maintenance\CheckHealth::class,
        Commands\Maintenance\CleanupExpiredData::class,
        Commands\Maintenance\ClearCache::class,
        Commands\Maintenance\OptimizeDatabase::class,
        Commands\Maintenance\RotateLogs::class,
        Commands\Maintenance\PurgeOldSessions::class,
        
        // Security Commands
        Commands\Security\RotateEncryptionKeys::class,
        Commands\Security\RunSecurityScan::class,
        Commands\Security\CheckSSLExpiry::class,
        Commands\Security\UpdateFirewallRules::class,
        Commands\Security\SyncLDAPUsers::class,
        Commands\Security\ValidateDataIntegrity::class,
        
        // Queue Commands
        Commands\Queue\ProcessSyncQueue::class,
        Commands\Queue\ProcessDeadLetterQueue::class,
        Commands\Queue\RetryFailedJobs::class,
        Commands\Queue\ClearQueue::class,
        
        // Report Commands
        Commands\Report\GenerateReports::class,
        Commands\Report\GenerateScheduledReports::class,
        Commands\Report\GenerateMetrics::class,
        Commands\Report\GenerateAuditReport::class,
        Commands\Report\GenerateComplianceReport::class,
        Commands\Report\SendWeeklyDigest::class,
        
        // Notification Commands
        Commands\Notification\SendNotifications::class,
        Commands\Notification\SendPendingAlerts::class,
        Commands\Notification\TestEmailDelivery::class,
        
        // Database Commands
        Commands\Database\MigrateFresh::class,
        Commands\Database\MigrateRollback::class,
        Commands\Database\ReindexElasticsearch::class,
        Commands\Database\UpdateVulnerabilityDatabase::class,
        
        // User Commands
        Commands\User\SyncPermissions::class,
        Commands\User\SyncUserPermissions::class,
        Commands\User\AssignDefaultRoles::class,
        Commands\User\CleanupInactiveUsers::class,
        
        // Integration Commands
        Commands\Integration\SyncFirebaseUsers::class,
        Commands\Integration\SyncExternalAPI::class,
        Commands\Integration\WebhookRetry::class,
        Commands\Integration\TestIntegrations::class,
        
        // AI Commands
        Commands\AI\TrainAIModels::class,
        Commands\AI\UpdateAIModels::class,
        Commands\AI\EvaluateAIModels::class,
        Commands\AI\RunAIPredictions::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup database hàng ngày lúc 2:00 AM
        $schedule->command('db:backup')->dailyAt('02:00');
        
        // Upload backup lên S3 hàng ngày lúc 3:00 AM
        $schedule->command('backup:upload-s3')->dailyAt('03:00');
        
        // Cleanup old backups hàng tuần
        $schedule->command('backup:cleanup --days=30')->weekly();
        
        // Cleanup expired data hàng ngày
        $schedule->command('cleanup:expired --days=90')->daily();
        
        // Clear cache hàng ngày lúc 1:00 AM
        $schedule->command('cache:clear-all')->dailyAt('01:00');
        
        // Optimize database hàng tuần
        $schedule->command('db:optimize')->weekly();
        
        // Rotate logs hàng ngày
        $schedule->command('logs:rotate --keep=7')->daily();
        
        // Purge old sessions mỗi giờ
        $schedule->command('session:purge --hours=24')->hourly();
        
        // Rotate encryption keys hàng tháng
        $schedule->command('keys:rotate')->monthly();
        
        // Run security scan hàng ngày
        $schedule->command('security:scan')->dailyAt('04:00');
        
        // Check SSL expiry hàng ngày
        $schedule->command('ssl:check --days=30')->daily();
        
        // Process sync queue mỗi 5 phút
        $schedule->command('queue:sync-process --limit=100')->everyFiveMinutes();
        
        // Process dead letter queue mỗi giờ
        $schedule->command('queue:dead-letter-process')->hourly();
        
        // Generate reports hàng ngày
        $schedule->command('reports:generate --type=daily')->dailyAt('06:00');
        
        // Generate scheduled reports hàng ngày
        $schedule->command('reports:scheduled')->dailyAt('07:00');
        
        // Generate metrics hàng giờ
        $schedule->command('metrics:generate')->hourly();
        
        // Generate audit report hàng tuần
        $schedule->command('audit:report')->weekly();
        
        // Generate compliance report hàng tháng
        $schedule->command('compliance:report --standard=iso27001')->monthly();
        
        // Send weekly digest vào thứ 2 hàng tuần
        $schedule->command('digest:weekly')->weekly()->mondays()->at('08:00');
        
        // Send pending notifications mỗi 10 phút
        $schedule->command('notifications:send')->everyTenMinutes();
        
        // Send pending alerts mỗi 5 phút
        $schedule->command('alerts:send')->everyFiveMinutes();
        
        // Sync users với Firebase hàng giờ
        $schedule->command('firebase:sync-users --direction=to-firebase')->hourly();
        
        // Retry failed webhooks mỗi 15 phút
        $schedule->command('webhook:retry --limit=50')->everyFifteenMinutes();
        
        // Test integrations hàng ngày
        $schedule->command('integration:test')->dailyAt('09:00');
        
        // Update AI models hàng tuần
        $schedule->command('ai:update-models')->weekly();
        
        // Run AI predictions hàng giờ
        $schedule->command('ai:predict --limit=50')->hourly();
        
        // Evaluate AI models hàng ngày
        $schedule->command('ai:evaluate')->daily();
        
        // Sync permissions hàng ngày
        $schedule->command('permissions:sync')->daily();
        
        // Cleanup inactive users hàng tháng
        $schedule->command('user:cleanup --days=180')->monthly();
        
        // Update vulnerability database hàng ngày
        $schedule->command('vuln:update')->daily();
        
        // Reindex Elasticsearch hàng tuần
        $schedule->command('search:reindex')->weekly();
        
        // Check system health mỗi 5 phút
        $schedule->command('system:health')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        
        require base_path('routes/console.php');
    }
}