<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'address',
        'phone',
        'closing_reminder',
        'closing_time',
    ];

    protected $casts = [
        'closing_reminder' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::firstOrCreate([], [
            'store_name' => 'KasirNih',
            'address' => '',
            'phone' => '',
            'closing_reminder' => false,
            'closing_time' => '21:00',
        ]);
    }
}
