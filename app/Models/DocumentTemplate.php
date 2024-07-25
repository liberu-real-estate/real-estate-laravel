<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\View;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'team_id',
        'type',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

 public static function findOrCreateTemplate(string $type, string $name, string $description, string $view_path)
    {
        $file_path = str_replace('.', '/', $view_path) . '.blade.php';
        return self::firstOrCreate(
            ['type' => $type],
            [
                'name' => $name,
                'description' => $description,
                'file_path' => $view_path,
                'team_id' => 1, // Assuming a default team ID, adjust as needed
                'content' => file_get_contents(resource_path('views/' . $file_path)),
            ]
        );
    }

    public function renderContent(array $data = [])
    {
        $view_path = str_replace('/', '.', str_replace('.blade.php', '', $this->file_path));
        return View::make($view_path, $data)->render();
    }
}
