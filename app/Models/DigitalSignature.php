<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSignature extends Model
{
    protected $table = 'digital_signatures';

    protected $fillable = [
        'user_id',
        'document_id',
        'signature_data',
        'signed_at',
        'team_id',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
