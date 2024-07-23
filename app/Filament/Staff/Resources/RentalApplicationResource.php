
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalApplications::route('/'),
            'create' => Pages\CreateRentalApplication::route('/create'),
            'edit' => Pages\EditRentalApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Property Management';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(['admin', 'staff']);
    }
}