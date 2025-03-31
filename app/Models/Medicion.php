<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Medicion extends Model
{
    use HasFactory;

    // Define el nombre de la tabla
    protected $table = 'mediciones';

    // Define los campos que se pueden llenar mediante asignación masiva
    protected $fillable = [
        'id',
        'nro_cuenta',
        'ruta',
        'orden',
        'medicion',
        'consumo',
        'fecha',
        'periodo',
        'foto_medidor',
        'estado_id',
        'codigo_medidor',
    ];

    // Define la relación con el modelo Estado
    public function obtenerEstado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Accesor para obtener el nombre del estado directamente
    public function getEstadoNombreAttribute()
    {
        // Si la relación obtenerEstado existe, devolvemos el nombre (tipo)
        return $this->obtenerEstado ? $this->obtenerEstado->tipo : null;
    }

    public function fotoMedicion(): HasOne
    {
        return $this->hasOne(FotoMedicion::class, 'medicion_id');
    }
}
