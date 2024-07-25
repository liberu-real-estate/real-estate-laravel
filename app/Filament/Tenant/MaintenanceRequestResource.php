<?php

namespace App\Filament\Tenant;

use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Notifications\Notification;

class MaintenanceRequestResource extends Resource
{
    protected static ?string $model = MaintenanceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('requested_date')
                    ->required(),
                Forms\Components\HasManyRepeater::make('workOrders')
                    ->relationship('workOrders')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('requested_date')
                    ->date(),
                Tables\Columns\TextColumn::make('workOrders.status')
                    ->label('Work Order Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('createWorkOrder')
                    ->action(function (MaintenanceRequest $record) {
                        WorkOrder::create([
                            'maintenance_request_id' => $record->id,
                            'description' => 'New work order for ' . $record->title,
                            'status' => 'pending',
                            'scheduled_date' => now(),
                        ]);
                        Notification::make()
                            ->title('Work Order Created')
                            ->success()
                            ->send();
                    })
                    ->label('Create Work Order'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMaintenanceRequests::route('/'),
            'create' => Pages\CreateMaintenanceRequest::route('/create'),
            'edit' => Pages\EditMaintenanceRequest::route('/{record}/edit'),
        ];
    }
}