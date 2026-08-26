<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Evidencias extends Model
{
    use HasFactory;

    protected $table = 'evidencias_eventos'; // Ajustado al nombre correcto de la tabla en la base de datos
    protected $primaryKey = 'id_evidencia';
    public $timestamps = false; // Si no tienes campos de timestamps (created_at, updated_at)

    protected $fillable = [
        'id_usuario',
        'id_evento',
        'archivo',
        'fecha_registro' // Cambiado a fecha_registro para que coincida con la migración
    ];

    public function evento()
    {
        return $this->belongsTo(Eventos::class, 'id_evento', 'id_evento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuario', 'id');
    }
}
