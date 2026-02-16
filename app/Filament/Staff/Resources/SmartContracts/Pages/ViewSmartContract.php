<?php

namespace App\Filament\Staff\Resources\SmartContracts\Pages;

use App\Filament\Staff\Resources\SmartContracts\SmartContractResource;
use App\Services\SmartContractService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;

class ViewSmartContract extends ViewRecord
{
    protected static string $resource = SmartContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sign_as_landlord')
                ->label('Sign as Landlord')
                ->icon('heroicon-o-pencil-square')
                ->color('success')
                ->visible(fn () => !$this->record->landlord_signed && auth()->user()->id === $this->record->landlord_id)
                ->requiresConfirmation()
                ->action(function () {
                    $smartContractService = app(SmartContractService::class);
                    try {
                        $smartContractService->signContract($this->record, auth()->user(), 'landlord');
                        Notification::make()
                            ->title('Contract signed successfully')
                            ->success()
                            ->send();
                        $this->refreshFormData(['landlord_signed']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to sign contract')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            
            Actions\Action::make('terminate')
                ->label('Terminate Contract')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->isActive())
                ->requiresConfirmation()
                ->modalHeading('Terminate Smart Contract')
                ->modalDescription('Are you sure you want to terminate this contract? This action cannot be undone.')
                ->action(function () {
                    $smartContractService = app(SmartContractService::class);
                    try {
                        $smartContractService->terminateContract($this->record, auth()->id());
                        Notification::make()
                            ->title('Contract terminated successfully')
                            ->success()
                            ->send();
                        $this->refreshFormData(['status', 'terminated_at']);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to terminate contract')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Contract Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('contract_address')
                            ->label('Contract Address')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'completed' => 'gray',
                                'terminated' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('blockchain_network')
                            ->label('Network')
                            ->badge(),
                        Infolists\Components\TextEntry::make('transaction_hash')
                            ->label('Transaction Hash')
                            ->copyable(),
                    ])->columns(2),

                Infolists\Components\Section::make('Parties')
                    ->schema([
                        Infolists\Components\TextEntry::make('landlord.name')
                            ->label('Landlord'),
                        Infolists\Components\IconEntry::make('landlord_signed')
                            ->label('Landlord Signed')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('tenant.name')
                            ->label('Tenant'),
                        Infolists\Components\IconEntry::make('tenant_signed')
                            ->label('Tenant Signed')
                            ->boolean(),
                    ])->columns(2),

                Infolists\Components\Section::make('Financial Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('rent_amount')
                            ->money('USD')
                            ->label('Monthly Rent'),
                        Infolists\Components\TextEntry::make('security_deposit')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('total_rent_paid')
                            ->money('USD')
                            ->label('Total Rent Paid'),
                        Infolists\Components\TextEntry::make('rent_payments_count')
                            ->label('Payment Count'),
                    ])->columns(2),

                Infolists\Components\Section::make('Lease Period')
                    ->schema([
                        Infolists\Components\TextEntry::make('lease_start_date')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('lease_end_date')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('deployed_at')
                            ->dateTime()
                            ->label('Deployed At'),
                        Infolists\Components\TextEntry::make('activated_at')
                            ->dateTime()
                            ->label('Activated At'),
                    ])->columns(2),

                Infolists\Components\Section::make('Property & Lease')
                    ->schema([
                        Infolists\Components\TextEntry::make('property.address')
                            ->label('Property'),
                        Infolists\Components\TextEntry::make('leaseAgreement.id')
                            ->label('Lease Agreement ID'),
                    ])->columns(2),
            ]);
    }
}
