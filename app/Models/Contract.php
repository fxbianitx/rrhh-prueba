<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',      // Empleado
        'contract_type', // Tipo de contrato
        'start_date',       // Fecha de inicio
        'end_date',         // Fecha de fin
    ];

    //! Obtener empleado del contrato
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
