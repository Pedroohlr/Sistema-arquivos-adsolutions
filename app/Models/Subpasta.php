<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subpasta extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'grupo_id',
        'nome',
        'usuario',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Grupo ao qual esta subpasta pertence
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Arquivos dentro desta subpasta
     */
    public function arquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class);
    }

    /**
     * Downloads realizados por este usuário
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }
}
