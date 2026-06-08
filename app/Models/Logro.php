<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logro extends Model
{
    public $timestamps = false;

    protected $table = 'logros';

    protected $fillable = [
        'slug',
        'nombre',
        'descripcion',
        'icono',
        'tipo',
        'condicion_valor',
    ];

    public function usuariosLogro(): HasMany
    {
        return $this->hasMany(LogroUsuario::class);
    }
}
