<?php

namespace App\Filament\Staff\Resources\PropertyTemplates;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\PropertyTemplates\Pages\ListPropertyTemplates;
use App\Filament\Staff\Resources\PropertyTemplates\Pages\CreatePropertyTemplate;
use App\Filament\Staff\Resources\PropertyTemplates\Pages\EditPropertyTemplate;
use App\Models\PropertyTemplate;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\PropertyTemplateResource\Pages;

class PropertyTemplateResource extends Resource
{
    protected static ?string $model = PropertyTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('content')
                    ->required()
                    ->label('Template Content')
                    ->helperText('Use placeholders like {title}, {description}, {price}, etc.')
                    ->rows(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListPropertyTemplates::route('/'),
            'create' => CreatePropertyTemplate::route('/create'),
            'edit' => EditPropertyTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
    }
}