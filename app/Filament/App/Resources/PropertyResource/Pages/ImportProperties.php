<?php

namespace App\Filament\App\Resources\PropertyResource\Pages;

use App\Filament\App\Resources\PropertyResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportProperties extends Page
{
    protected static string $resource = PropertyResource::class;

    protected static string $view = 'filament.app.resources.property-resource.pages.import-properties';

    public ?array $data = [];
    public $csvHeaders = [];
    public $columnMapping = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('csv_file')
                    ->label('CSV File')
                    ->acceptedFileTypes(['text/csv'])
                    ->maxSize(5120)
                    ->directory('csv-imports')
                    ->required()
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            $this->loadCsvHeaders($state);
                        }
                    }),
            ]);
    }

    public function loadCsvHeaders($filePath)
    {
        $csv = Reader::createFromPath(Storage::path($filePath), 'r');
        $csv->setHeaderOffset(0);
        $this->csvHeaders = $csv->getHeader();

        $this->columnMapping = collect($this->getPropertyFields())->mapWithKeys(function ($field) {
            return [$field => ''];
        })->toArray();
    }

    public function getPropertyFields()
    {
        return [
            'title', 'description', 'location', 'price', 'bedrooms', 'bathrooms',
            'area_sqft', 'year_built', 'property_type', 'status', 'list_date', 'sold_date'
        ];
    }

    protected function getFormSchema(): array
    {
        $schema = [
            FileUpload::make('csv_file')
                ->label('CSV File')
                ->acceptedFileTypes(['text/csv'])
                ->maxSize(5120)
                ->directory('csv-imports')
                ->required()
                ->afterStateUpdated(function ($state) {
                    if ($state) {
                        $this->loadCsvHeaders($state);
                    }
                }),
        ];

        foreach ($this->getPropertyFields() as $field) {
            $schema[] = Select::make("columnMapping.{$field}")
                ->label(ucfirst(str_replace('_', ' ', $field)))
                ->options(array_combine($this->csvHeaders, $this->csvHeaders))
                ->searchable();
        }

        return $schema;
    }

    public function import()
    {
        $data = $this->form->getState();
    
        $csv = Reader::createFromPath(Storage::path($data['csv_file']), 'r');
        $csv->setHeaderOffset(0);
    
        $records = $csv->getRecords();
    
        foreach ($records as $record) {
            $propertyData = [];
            foreach ($this->columnMapping as $field => $csvColumn) {
                if (!empty($csvColumn) && isset($record[$csvColumn])) {
                    $propertyData[$field] = $record[$csvColumn];
                }
            }
    
            // Validate and create the property
            try {
                $property = PropertyResource::getModel()::create($propertyData);
                $this->notify('success', "Property '{$property->title}' imported successfully.");
            } catch (\Exception $e) {
                $this->notify('error', "Failed to import property: {$e->getMessage()}");
            }
        }
    
        // Delete the temporary CSV file
        Storage::delete($data['csv_file']);
    
        $this->notify('success', 'Import completed.');
        $this->redirect(PropertyResource::getUrl('index'));
    }
}
