<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;


   protected $fillable = [
    'id_tipo_evento',
    'id_evento',
    'id_creador',
    'id_director',
    'id_maestro',
    'nombre_evento',
    'fecha_inicio',
    'fecha_fin',
    'descripcion',
    'duracion_horas',
    'modalidad',
    'estatus',
    'folio',
    'observaciones',
    'academia',
];

    public function tipoEvento() {
        return $this->belongsTo(TipoEvento::class, 'id_tipo_evento');
    }

    public function eventoHorario()
    {
        return $this->hasMany(EventoHorario::class, 'id_evento', 'id_evento');
    }

    public function creador()
    {
        return $this->belongsTo(Usuarios::class, 'id_creador', 'id');
    }

    public function director()
    {
        return $this->belongsTo(Usuarios::class, 'id_director', 'id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencias::class, 'id_evento', 'id_evento');
    }


    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_evento', 'id_evento');
    }


}
