<?php

namespace App\Filament\Tenant\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RelationshipRepeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages\ListMaintenanceRequests;
use App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages\CreateMaintenanceRequest;
use App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages\EditMaintenanceRequest;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Services\NotificationService;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Filament\Tenant\Resources\MaintenanceRequestResource\Pages;

class MaintenanceRequestResource extends Resource
{
    protected static ?string $model = MaintenanceRequest::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->required(),
                DatePicker::make('requested_date')
                    ->required(),
                Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                RelationshipRepeater::make('workOrders')
                    ->relationship('workOrders')
                    ->schema([
                        TextInput::make('description')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        DatePicker::make('scheduled_date')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('status'),
                TextColumn::make('requested_date')
                    ->date(),
                TextColumn::make('property.title')
                    ->label('Property'),
                TextColumn::make('workOrders.status')
                    ->label('Work Order Status'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('createWorkOrder')
                    ->action(function (MaintenanceRequest $record, NotificationService $notificationService) {
                        $workOrder = WorkOrder::create([
                            'maintenance_request_id' => $record->id,
                            'description' => 'New work order for ' . $record->title,
                            'status' => 'pending',
                            'scheduled_date' => now(),
                        ]);
                        $notificationService->notifyTenantWorkOrderCreated($record->tenant, $workOrder);
                        Notification::make()
                            ->title('Work Order Created')
                            ->success()
                            ->send();
                    })
                    ->label('Create Work Order'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaintenanceRequests::route('/'),
            'create' => CreateMaintenanceRequest::route('/create'),
            'edit' => EditMaintenanceRequest::route('/{record}/edit'),
        ];
    }
}