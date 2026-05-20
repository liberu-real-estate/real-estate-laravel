<?php

namespace App\Filament\Staff\Resources\Leads;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Staff\Resources\Leads\Pages\ListLeads;
use App\Filament\Staff\Resources\Leads\Pages\CreateLead;
use App\Filament\Staff\Resources\Leads\Pages\EditLead;
use App\Filament\Staff\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('phone'),
                Select::make('interest')
                    ->options([
                        'buying' => 'Buying',
                        'selling' => 'Selling',
                        'renting' => 'Renting',
                        'other' => 'Other',
                    ]),
                Textarea::make('message'),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'lost' => 'Lost',
                        'converted' => 'Converted',
                    ])
                    ->required(),
                TextInput::make('score')
                    ->disabled()
                    ->helperText('Score is automatically calculated'),
                Select::make('category')
                    ->options([
                        'hot' => 'Hot',
                        'warm' => 'Warm',
                        'cold' => 'Cold',
                    ])
                    ->required(),
                DateTimePicker::make('last_contacted_at'),
                Repeater::make('activities')
                    ->relationship('activities')
                    ->schema([
                        TextInput::make('type'),
                        Textarea::make('description'),
                        DateTimePicker::make('created_at'),
                    ])
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('interest'),
                TextColumn::make('status'),
                TextColumn::make('score')->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'lost' => 'Lost',
                        'converted' => 'Converted',
                    ]),
                SelectFilter::make('interest')
                    ->options([
                        'buying' => 'Buying',
                        'selling' => 'Selling',
                        'renting' => 'Renting',
                        'other' => 'Other',
                    ]),
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
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
