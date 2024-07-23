
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
}