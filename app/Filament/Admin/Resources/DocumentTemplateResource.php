<?php

namespace App\Filament\Resources;

use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Table\Columns\TextColumn::make('name')->label('Name'),
                Table\Columns\TextColumn::make('file_path')->label('File Path'),
                Table\Columns\TextColumn::make('description')->label('Description'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
    }
}
