
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/properties/{property}/apply', [TenancyApplicationController::class, 'create'])->name('tenancy.apply');
Route::post('/properties/{property}/apply', [TenancyApplicationController::class, 'store'])->name('tenancy.store');

// ... other existing routes ...