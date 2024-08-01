<?php

namespace App\Filament\Staff\Resources;

use App\Models\PropertyTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Staff\Resources\PropertyTemplateResource\Pages;

class PropertyTemplateResource extends Resource
{
    protected static ?string $model = PropertyTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-template';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPropertyTemplates::route('/'),
            'create' => Pages\CreatePropertyTemplate::route('/create'),
            'edit' => Pages\EditPropertyTemplate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string
    {
        return __('Administration');
    }
}