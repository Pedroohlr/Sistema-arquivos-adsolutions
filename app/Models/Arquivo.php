<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arquivo extends Model
{
    protected $fillable = [
        'nome',
        'nome_original',
        'caminho',
        'tamanho',
        'tipo_mime',
        'grupo_id',
        'subpasta_id',
    ];

    protected function casts(): array
    {
        return [
            'tamanho' => 'integer',
        ];
    }

    /**
     * Grupo ao qual este arquivo pertence
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Subpasta onde este arquivo está (null se estiver na raiz do grupo)
     */
    public function subpasta(): BelongsTo
    {
        return $this->belongsTo(Subpasta::class);
    }

    /**
     * Histórico de downloads deste arquivo
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    /**
     * Verifica se o arquivo está na raiz do grupo
     */
    public function isRaiz(): bool
    {
        return is_null($this->subpasta_id);
    }

    /**
     * Formata o tamanho do arquivo
     */
    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
