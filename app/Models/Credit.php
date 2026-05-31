<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activations;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'activation_id',
        'amount',
        'status',
    ];

    public function my_activation()
    {
        return $this->belongsTo(Activations::class,'activation_id', 'id');;
    }

}
