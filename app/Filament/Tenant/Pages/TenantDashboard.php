
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Tenant\Widgets\TenantStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Tenant\Widgets\RecentMaintenanceRequests::class,
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
