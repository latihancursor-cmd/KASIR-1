<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'code'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Generate kode kategori otomatis berdasarkan 3 huruf pertama
    public static function generateCode($name)
    {
        $code = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $counter = 1;
        $originalCode = $code;
        
        while (self::where('code', $code)->exists()) {
            $code = $originalCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $code;
    }
}
