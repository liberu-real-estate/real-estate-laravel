<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents the site settings for the real estate application.
 *
 * @property int $id
 * @property string $name
 * @property string $currency
 * @property string $default_language
 * @property string $address
 * @property string $country
 * @property string $email
 */
class SiteSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'currency',
        'default_language',
        'address',
        'country',
        'email',
        'sales_commission_percentage',
        'lettings_commission_percentage',
    ];
}