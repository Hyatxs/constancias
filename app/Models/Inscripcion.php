<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones_eventos';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;
    protected $dates = ['created_at', 'updated_at'];
    
    protected $fillable = [
        'id_usuario',
        'id_evento',
        'estatus',
        'fecha_inscripcion',
        'fecha_envio',
    ];

    public function evento()
    {
        return $this->belongsTo(Eventos::class, 'id_evento', 'id_evento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuario', 'id_usuario');
    }
}
