<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DocumentTemplateResource\Pages\ListDocumentTemplates;
use App\Filament\Resources\DocumentTemplateResource\Pages\CreateDocumentTemplate;
use App\Filament\Resources\DocumentTemplateResource\Pages\EditDocumentTemplate;
use App\Filament\Resources\DocumentTemplateResource\Pages;
use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535),
                Textarea::make('content')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('type'),
                TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => ListDocumentTemplates::route('/'),
            'create' => CreateDocumentTemplate::route('/create'),
            'edit' => EditDocumentTemplate::route('/{record}/edit'),
        ];
    }
}