<?php

namespace App\Jobs;

use App\Convert;
use App\Enums\ConvertStatus;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ConvertToNightcore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $convert;

    public function __construct(Convert $convert)
    {
        $this->convert = $convert;
    }

    public function handle()
    {
        try {
            // start convert
            $this->convert->status = ConvertStatus::Converting;
            $this->convert->save();
            // convert progress
            \FFMpeg::open('temp/' . $this->convert->file_name)
                ->addFilter(
                    '-af',
                    implode(',', [
                        // set tempo & pitch
                        'rubberband=tempo=' . $this->convert->getOption('tempo') . ':pitch=' . $this->convert->getOption('pitch'),
                        // set volume
                        'volume=' . $this->convert->getOption('volume')
                    ])
                )
                // export result
                ->export()
                ->toDisk('public')
                ->informat(new Mp3())
                ->save('converts/' . $this->convert->file_name);
            // convert success
            $this->convert->status = ConvertStatus::Converted;
            $this->convert->expired_at = now()->addHour();
            $this->convert->save();
        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            // fail to convert
            $this->convert->status = ConvertStatus::Failed;
            $this->convert->save();
        } finally {
            // remove source audio
            \Storage::delete('temp/' . $this->convert->file_name);
        }
    }
}
