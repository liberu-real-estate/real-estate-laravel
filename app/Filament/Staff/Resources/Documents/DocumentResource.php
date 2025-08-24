<?php

namespace App\Filament\Staff\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\DocumentResource\Pages\ListDocuments;
use App\Filament\Staff\Resources\DocumentResource\Pages\CreateDocument;
use App\Filament\Staff\Resources\DocumentResource\Pages\EditDocument;
use App\Filament\Staff\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('property_id')
                    ->relationship('property', 'title')
                    ->native(false),
                Select::make('categories')
                    ->multiple()
                    ->preload()
                    ->relationship('categories', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(512)
                            ->rows(5),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action->modalHeading('Create document category');
                    }),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                FileUpload::make('document')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('file_type'),
                TextColumn::make('size')->formatStateUsing(fn ($state) => number_format($state / 1024 / 1024, 2) . ' MB'),
                TextColumn::make('user.name'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
