<?php

namespace App\Console\Commands;

use App\Convert;
use App\Enums\ConvertStatus;
use Illuminate\Console\Command;

class ConvertClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the expired convert audios.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $expiredConverts = Convert::whereStatus(ConvertStatus::Converted)->where('expired_at', '<=', now());
        // get expired audios
        $expiredAudios = $expiredConverts->pluck('file_name')->map(function ($fileName) {
            return 'converts/' . $fileName;
        });
        // set expired status
        $expiredConverts->update(['status' => ConvertStatus::Expired]);
        // delete expired audios
        \Storage::disk('public')->delete($expiredAudios->toArray());

        $this->info('Expired converts cleared successfully!');
    }
}
