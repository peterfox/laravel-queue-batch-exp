<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('qt', function () {
    \Illuminate\Support\Facades\Bus::batch([
        function () {
            ray()->send('first');
        },
        function () {
            ray()->send('second');
        },
    ])
        ->then(function (\Illuminate\Bus\Batch $batch) {
            ray()->send($batch->finishedAt);

            if ($batch->processedJobs() >= 3) {
                return;
            }

            $batch->add([
                function () {
                    \Illuminate\Support\Sleep::sleep(15);
                    ray()->send('third');
                },
            ]);

            $batch = $batch->fresh();

            ray($batch->finishedAt);
        })
        ->dispatch();

    $this->comment('Fired');
})
    ->purpose('Test Batching');
