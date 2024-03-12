<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->required(),
                Forms\Components\Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->nullable(),
                Forms\Components\Textarea::make('notes')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('staff.name')->label('Staff Member'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('notes')->limit(50),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
            'manageCalendar' => Pages\ManageBookingCalendar::route('/calendar'),
        ];
    }
}
