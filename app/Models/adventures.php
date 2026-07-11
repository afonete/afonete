<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
class Adventures extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plan',
        'min_amount',
        'max_amount',
        'percentage',
        'percentage_range',
        'duration',
        'total_return',
        'currency',
        'current_price'
    ];

    public function investments()
    {
        return $this->hasMany(Payment::class,"package","id");
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

}



?>
