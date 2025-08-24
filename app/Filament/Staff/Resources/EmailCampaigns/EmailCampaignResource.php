<?php

namespace App\Filament\Staff\Resources\EmailCampaigns;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Staff\Resources\EmailCampaigns\Pages\ListEmailCampaigns;
use App\Filament\Staff\Resources\EmailCampaigns\Pages\CreateEmailCampaign;
use App\Filament\Staff\Resources\EmailCampaigns\Pages\EditEmailCampaign;
use App\Filament\Staff\Resources\EmailCampaignResource\Pages;
use App\Models\EmailCampaign;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailCampaignResource extends Resource
{
    protected static ?string $model = EmailCampaign::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sent' => 'Sent',
                    ])
                    ->required(),
                DateTimePicker::make('scheduled_at')
                    ->required()
                    ->visible(fn ($get) => $get('status') === 'scheduled'),
                Select::make('target_leads')
                    ->multiple()
                    ->relationship('leads', 'name')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('scheduled_at')->dateTime()->sortable(),
                TextColumn::make('sent_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sent' => 'Sent',
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
            'index' => ListEmailCampaigns::route('/'),
            'create' => CreateEmailCampaign::route('/create'),
            'edit' => EditEmailCampaign::route('/{record}/edit'),
        ];
    }
}