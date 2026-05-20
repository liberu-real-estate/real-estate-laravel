<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }
}