<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TokenCleanupService;

class CleanupFCMTokens extends Command
{
    protected $signature = 'fcm:cleanup-tokens';
    protected $description = 'Clean up invalid FCM tokens';

    protected $tokenCleanupService;

    public function __construct(TokenCleanupService $tokenCleanupService)
    {
        parent::__construct();
        $this->tokenCleanupService = $tokenCleanupService;
    }

    public function handle()
    {
        $this->info('Starting FCM token cleanup...');
        $this->tokenCleanupService->cleanupInvalidTokens();
        $this->info('FCM token cleanup completed.');
    }
}