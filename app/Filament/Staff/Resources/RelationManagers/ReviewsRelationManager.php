
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
                ]),
            ]);
    }
}