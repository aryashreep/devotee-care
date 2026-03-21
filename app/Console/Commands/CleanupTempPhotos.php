<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:cleanup-temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up temporary registration photos older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up temporary photos...');

        $tempPath = 'temp_photos';

        if (!Storage::disk('public')->exists($tempPath)) {
            $this->info('No temp_photos directory found.');
            return;
        }

        $files = Storage::disk('public')->files($tempPath);
        $count = 0;
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            if ($lastModified->diffInHours($now) >= 24) {
                Storage::disk('public')->delete($file);
                $count++;
            }
        }

        $this->info("Cleaned up {$count} temporary photos.");
    }
}
