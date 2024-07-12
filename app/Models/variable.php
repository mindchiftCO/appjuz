<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variable extends Model
{
    use HasFactory;

    protected $fillable = ['auto_id', 'nombre'];

    public function auto()
    {
        return $this->belongsTo(auto::class);
    }
}
