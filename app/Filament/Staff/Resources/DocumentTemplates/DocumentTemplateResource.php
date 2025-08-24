<?php

namespace App\Filament\Staff\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Staff\Resources\DocumentTemplateResource\Pages\ListDocumentTemplates;
use App\Filament\Staff\Resources\DocumentTemplateResource\Pages\CreateDocumentTemplate;
use App\Filament\Staff\Resources\DocumentTemplateResource\Pages\EditDocumentTemplate;
use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Staff\Resources\DocumentTemplateResource\Pages;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Template Name'),
                FileUpload::make('file_path')
                    ->required()
                    ->label('Template File')
                    ->disk('public')
                    ->directory('document_templates')
                    ->acceptedFileTypes(['.doc', '.docx', '.pdf']),
                Textarea::make('description')
                    ->label('Description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('file_path')->label('File Path'),
                TextColumn::make('description')->label('Description'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
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
