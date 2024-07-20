public function register(): void
{
    $this->reportable(function (Throwable $e) {
        if ($e instanceof \App\Exceptions\BooминApiException) {
            Log::error('Boomin API Error: ' . $e->getMessage());
        }
    });
}