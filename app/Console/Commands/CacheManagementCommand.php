<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class CacheManagementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage {action : Action to perform (clear, warm, status)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application cache (clear, warm, status)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'clear':
                $this->clearCache();
                break;
            case 'warm':
                $this->warmCache();
                break;
            case 'status':
                $this->showCacheStatus();
                break;
            default:
                $this->error("Unknown action: {$action}");
                return 1;
        }

        return 0;
    }

    /**
     * Clear all cache
     */
    private function clearCache()
    {
        $this->info('Clearing all cache...');

        try {
            CacheService::clear();
            $this->info('Cache cleared successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Warm up cache with frequently accessed data
     */
    private function warmCache()
    {
        $this->info('Warming up cache...');

        try {
            // Warm up UI data
            $this->warmUIData();

            // Warm up statistics data
            $this->warmStatsData();

            $this->info('Cache warmed up successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to warm cache: ' . $e->getMessage());
        }
    }

    /**
     * Warm up UI data cache
     */
    private function warmUIData()
    {
        $this->line('Warming up UI data...');

        // Teams
        \App\Models\Team::select('id', 'name')->orderBy('name')->get();

        // Tournaments
        \App\Models\Tournament::select('id', 'name')->orderBy('name')->get();

        // Venues
        \App\Models\Venue::active()->select('id', 'name', 'city', 'capacity')->orderBy('name')->get();
    }

    /**
     * Warm up statistics data cache
     */
    private function warmStatsData()
    {
        $this->line('Warming up statistics data...');

        // Top players
        \App\Models\Player::select('id', 'name', 'avatar', 'position', 'rating', 'goals_scored', 'team_id')
            ->with(['team:id,name'])
            ->orderBy('rating', 'desc')
            ->orderBy('goals_scored', 'desc')
            ->take(4)
            ->get();

        // Top teams
        \App\Models\Team::select('id', 'name', 'logo', 'rating', 'trophies_count')
            ->orderBy('rating', 'desc')
            ->orderBy('trophies_count', 'desc')
            ->take(4)
            ->get();
    }

    /**
     * Show cache status
     */
    private function showCacheStatus()
    {
        $this->info('Cache Status:');

        $drivers = ['file', 'redis', 'memcached'];
        $currentDriver = config('cache.default');

        $this->line("Current driver: {$currentDriver}");

        foreach ($drivers as $driver) {
            $status = $driver === $currentDriver ? '✓ Active' : '✗ Inactive';
            $this->line("  {$driver}: {$status}");
        }

        // Show cache size (if file driver)
        if ($currentDriver === 'file') {
            $cachePath = storage_path('framework/cache/data');
            if (is_dir($cachePath)) {
                $size = $this->getDirectorySize($cachePath);
                $this->line("Cache size: {$size}");
            }
        }
    }

    /**
     * Get directory size in human readable format
     */
    private function getDirectorySize($path)
    {
        $size = 0;
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = max($size, 0);
        $pow = floor(($size ? log($size) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $size /= pow(1024, $pow);

        return round($size, 2) . ' ' . $units[$pow];
    }
}
