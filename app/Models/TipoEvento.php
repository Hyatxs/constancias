<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    use HasFactory;
    protected $table = 'tipos_eventos';
    protected $primaryKey = 'id_tipo_evento';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function eventos() {
        return $this->hasMany(Eventos::class, 'id_tipo_evento');
    }
}
