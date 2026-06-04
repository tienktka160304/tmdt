<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\AutoCancelOrder;
use App\Models\Discount;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Artisan::command('discount:expire', function () {
//     Discount::where('end_date', '<', now())->update(['status' => 0]);
//     $this->info('Expired discounts have been updated.');
// })->purpose('Update expired discounts')->hourly();
