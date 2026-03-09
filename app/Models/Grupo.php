<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Subpastas deste grupo
     */
    public function subpastas(): HasMany
    {
        return $this->hasMany(Subpasta::class);
    }

    /**
     * Arquivos deste grupo (na raiz - sem subpasta)
     */
    public function arquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class)->whereNull('subpasta_id');
    }

    /**
     * Todos os arquivos deste grupo (incluindo os das subpastas)
     */
    public function todosArquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class);
    }
}
 