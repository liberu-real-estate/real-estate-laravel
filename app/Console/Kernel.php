protected function schedule(Schedule $schedule): void
{
    // Sync properties with RightMove every hour
    $schedule->call(function () {
        $rightMoveService = app(RightMoveService::class);
        $rightMoveService->syncAllProperties();
    })->hourly();

    // Sync properties with Boomin
    $schedule->call(function () {
        $booминService = app(BooминService::class);
        $booминService->syncAllProperties();
    })->cron($this->getBooминSyncFrequency());
}

protected function getBooминSyncFrequency(): string
{
    $settings = BooминSettings::first();
    $frequency = $settings ? $settings->sync_frequency : 1;
    return "0 */{$frequency} * * *";
}