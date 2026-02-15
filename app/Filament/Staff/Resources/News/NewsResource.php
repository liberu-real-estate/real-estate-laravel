<?php

namespace App\Filament\Staff\Resources\News;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Staff\Resources\News\Pages\ListNews;
use App\Filament\Staff\Resources\News\Pages\CreateNews;
use App\Filament\Staff\Resources\News\Pages\EditNews;
use App\Models\News;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, Set $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(News::class, 'slug', ignoreRecord: true),
                Textarea::make('excerpt')
                    ->maxLength(500)
                    ->rows(3)
                    ->helperText('A brief summary of the news article (optional)'),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'link',
                        'heading',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'codeBlock',
                    ]),
                DateTimePicker::make('published_at')
                    ->label('Publish Date & Time')
                    ->helperText('Leave empty to save as draft'),
                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->id()),
                Toggle::make('is_featured')
                    ->label('Featured Article')
                    ->helperText('Featured articles will be highlighted on the homepage'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Published')
                    ->badge()
                    ->color(fn ($state) => $state === null ? 'gray' : ($state->isFuture() ? 'warning' : 'success'))
                    ->formatStateUsing(fn ($state) => $state === null ? 'Draft' : $state->format('M d, Y H:i')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_featured')
                    ->label('Featured'),
                SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('published')
                    ->label('Published Status')
                    ->queries(
                        true: fn ($query) => $query->published(),
                        false: fn ($query) => $query->whereNull('published_at'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::published()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
