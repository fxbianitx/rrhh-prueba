<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',   // Nombre
        'last_name',    // Apellido
        'dni',          // DNI
        'email',        // Correo
        'birth_date',    // Fecha de nacimiento
        'area_id',      // Area
        'position_id',  // Cargo
        'location_id',  // Local
    ];

    //! Obtener area asignada
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    //! Obtener cargo asignado
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    //! Obtener local asignado
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    //! Obtener historial de contratos
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
