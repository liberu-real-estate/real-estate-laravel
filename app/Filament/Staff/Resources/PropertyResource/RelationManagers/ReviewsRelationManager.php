public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approved')
                    ->options([
                        '1' => 'Approved',
                        '0' => 'Not Approved',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->action(fn ($record) => $record->update(['approved' => true]))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->approved),
                Tables\Actions\Action::make('unapprove')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn ($record) => $record->update(['approved' => false]))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => !$record->approved),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->action(fn ($records) => $records->each->update(['approved' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unapprove')
                        ->action(fn ($records) => $records->each->update(['approved' => false]))
                        ->requiresConfirmation(),