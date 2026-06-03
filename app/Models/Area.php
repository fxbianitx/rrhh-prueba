<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Nombre
    ];

    //! Obtener empleados del area
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
