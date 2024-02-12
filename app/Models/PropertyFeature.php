<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    protected $primaryKey = 'feature_id';

    protected $fillable = [
        'property_id',
        'feature_name',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}

