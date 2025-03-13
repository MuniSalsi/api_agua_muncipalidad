<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoMedicion extends Model
{
    use HasFactory;

    protected $table = 'fotos_mediciones';
    protected $fillable = [
        'url_foto',
        'medicion_id',
    ];

    public function medicion(): BelongsTo
    {
        return $this->belongsTo(Medicion::class, 'medicion_id');
    }
}
