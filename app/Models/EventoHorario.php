<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoHorario extends Model
{
    use HasFactory;

    protected $table = 'eventos_horarios';
    protected $primaryKey = 'id_evento_horario';
    public $timestamps = false;

    protected $fillable = [
        'id_evento',
        'dia',
        'ubicacion',
        'observaciones',
        'hora_inicio',
        'hora_fin',
    ];

    public function eventos()
    {
        return $this->belongsTo(Eventos::class, 'id_evento', 'id_evento');
    }
}
