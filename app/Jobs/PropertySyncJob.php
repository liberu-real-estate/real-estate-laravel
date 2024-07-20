class PropertySyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(OnTheMarketService $onTheMarketService)
    {
        $properties = Property::all();
        $syncedCount = 0;
        $failedCount = 0;

        foreach ($properties as $property) {
            try {
                if ($property->onthemarket_id) {
                    $result = $onTheMarketService->updateProperty($property);
                } else {
                    $result = $onTheMarketService->uploadProperty($property);
                }

                if ($result) {
                    $property->last_synced_at = now();
                    $property->save();
                    Log::info("Property {$property->id} synced successfully with OnTheMarket.");
                    $syncedCount++;
                } else {
                    Log::error("Failed to sync property {$property->id} with OnTheMarket.");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Error syncing property {$property->id} with OnTheMarket: " . $e->getMessage());
                $failedCount++;
            }
        }

        $status = $failedCount === 0 ? 'Success' : 'Partial Success';
        $message = "Synced: {$syncedCount}, Failed: {$failedCount}";
        
        // Notify admin users
        $adminUsers = \App\Models\User::where('is_admin', true)->get();
        \Notification::send($adminUsers, new PropertySyncNotification($status, $message));
    }
}