<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class FCpackage extends Model
{
    use HasFactory;

    protected $table = "fcspackages";
    protected $fillable = [
        "name",
        "price",
        "default_token"
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
