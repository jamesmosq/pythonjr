<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionPlataforma extends Model
{
    protected $table = 'configuracion_plataforma';

    protected $fillable = ['clave', 'valor', 'tipo', 'descripcion'];

    public static function valor(string $clave, mixed $default = null): mixed
    {
        $config = static::where('clave', $clave)->first();
        if (! $config) {
            return $default;
        }

        return match ($config->tipo) {
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'number'  => (int) $config->valor,
            'float'   => (float) $config->valor,
            default   => $config->valor,
        };
    }

    public static function set(string $clave, mixed $valor): void
    {
        static::where('clave', $clave)->update(['valor' => (string) $valor]);
    }
}
