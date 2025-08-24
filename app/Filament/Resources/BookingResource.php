<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BookingResource\Pages\ListBookings;
use App\Filament\Resources\BookingResource\Pages\CreateBooking;
use App\Filament\Resources\BookingResource\Pages\EditBooking;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_id')
                    ->relationship('property', 'title')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('time')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact')
                    ->required()
                    ->maxLength(255),
                Textarea::make('notes')
                    ->maxLength(65535),
                Select::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'rescheduled' => 'Rescheduled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.title'),
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('time'),
                TextColumn::make('user.name'),
                TextColumn::make('name'),
                TextColumn::make('status'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'rescheduled' => 'Rescheduled',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('cancel')
                    ->action(fn (Booking $record) => $record->cancel())
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record) => $record->canBeCancelled()),
                Action::make('reschedule')
                    ->schema([
                        DatePicker::make('new_date')
                            ->required(),
                        TimePicker::make('new_time')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->reschedule($data['new_date'], $data['new_time']);
                    })
                    ->visible(fn (Booking $record) => $record->canBeRescheduled()),
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
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
