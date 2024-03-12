<?php

namespace App\Filament\Resources;

use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class DocumentTemplateResource extends Resource
/**
 * This file defines the Filament resource for Document Templates, including its form and table configurations.
 */
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static function form(Form $form): Form
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

    protected static function table(Table $table): Table
    /**
     * Defines the form schema for Document Templates.
     * 
     * @param Form $form The form builder instance.
     * @return Form The configured form instance.
     */
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
    /**
     * Defines the table schema for Document Templates.
     * 
     * @param Table $table The table builder instance.
     * @return Table The configured table instance.
     */
