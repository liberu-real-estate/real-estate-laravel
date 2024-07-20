<?php

namespace App\Filament\App\Resources\PropertyResource\Pages;

use App\Filament\App\Resources\PropertyResource;
use App\Services\OnTheMarketService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class OnTheMarketIntegration extends Page
{
    protected static string $resource = PropertyResource::class;

    protected static string $view = 'filament.resources.property-resource.pages.on-the-market-integration';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'api_key' => config('services.onthemarket.api_key'),
            'sync_frequency' => config('services.onthemarket.sync_frequency', 'hourly'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('api_key')
                    ->label('OnTheMarket API Key')
                    ->required(),
                Select::make('sync_frequency')
                    ->label('Sync Frequency')
                    ->options([
                        'hourly' => 'Hourly',
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                    ])
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Update the configuration
        config(['services.onthemarket.api_key' => $data['api_key']]);
        config(['services.onthemarket.sync_frequency' => $data['sync_frequency']]);

        // You might want to save these to the database or .env file for persistence

        Notification::make()
            ->title('OnTheMarket settings updated successfully')
            ->success()
            ->send();
    }

    public function syncNow(): void
    {
        $onTheMarketService = app(OnTheMarketService::class);
        $results = $onTheMarketService->syncAllProperties();

        $successCount = count(array_filter($results, fn($result) => $result['status'] !== 'error'));
        $errorCount = count($results) - $successCount;

        Notification::make()
            ->title("Sync completed: {$successCount} succeeded, {$errorCount} failed")
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'OnTheMarket Integration';
    }
}