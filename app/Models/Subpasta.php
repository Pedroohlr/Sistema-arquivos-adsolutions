<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subpasta extends Model
{
    protected $fillable = [
        'grupo_id',
        'nome',
    ];

    /**
     * Grupo ao qual esta subpasta pertence
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Clientes (usuários) com acesso a esta subpasta
     */
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'subpasta_cliente');
    }

    /**
     * Arquivos dentro desta subpasta
     */
    public function arquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class);
    }
}

