<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class removeTrashData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-trash-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $threshold = Carbon::now()->subDays(30);

        $deleted = Task::where('status', 'trash')
            ->where('updated_at', '<=', $threshold)
            ->delete();

        $this->info("Deleted $deleted trashed task(s) older than 30 days.");
    }
}
