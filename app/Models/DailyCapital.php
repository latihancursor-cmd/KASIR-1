<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCapital extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'initial_capital',
        'expenses',
        'counted_cash',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'initial_capital' => 'decimal:2',
        'expenses' => 'decimal:2',
        'counted_cash' => 'decimal:2',
    ];
}
