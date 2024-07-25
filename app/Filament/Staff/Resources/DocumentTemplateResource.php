<?php

namespace App\Filament\Staff\Resources;

use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Filament\Staff\Resources\DocumentTemplateResource\Pages;

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
                Select::make('type')
                    ->required()
                    ->options([
                        'lease_agreement' => 'Lease Agreement',
                        'uk_ast_agreement' => 'UK AST Agreement',
                        'section_8_notice' => 'Section 8 Notice',
                        'section_21_notice' => 'Section 21 Notice',
                    ])
                    ->label('Template Type'),
                FileUpload::make('file_path')
                    ->label('Template File')
                    ->disk('public')
                    ->directory('document_templates')
                    ->acceptedFileTypes(['.doc', '.docx', '.pdf']),
                Textarea::make('description')
                    ->label('Description'),
                Textarea::make('content')
                    ->label('Template Content')
                    ->rows(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Table\Columns\TextColumn::make('name')->label('Name'),
                Table\Columns\TextColumn::make('type')->label('Type'),
                Table\Columns\TextColumn::make('description')->label('Description'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'lease_agreement' => 'Lease Agreement',
                        'uk_ast_agreement' => 'UK AST Agreement',
                        'section_8_notice' => 'Section 8 Notice',
                        'section_21_notice' => 'Section 21 Notice',
                    ]),
            ]);
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentTemplates::route('/'),
            'create' => Pages\CreateDocumentTemplate::route('/create'),
            'edit' => Pages\EditDocumentTemplate::route('/{record}/edit'),
        ];
    }
}
