<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a digital signature associated with a user and a document.
 */

class DigitalSignature extends Model
{
    protected $table = 'digital_signatures';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
