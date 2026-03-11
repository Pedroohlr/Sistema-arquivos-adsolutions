<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Download extends Model
{
    protected $fillable = [
        'arquivo_id',
        'cliente_id',
        'usuario',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'downloaded_at' => 'datetime',
        ];
    }

    /**
     * Arquivo que foi baixado
     */
    public function arquivo(): BelongsTo
    {
        return $this->belongsTo(Arquivo::class);
    }

    /**
     * Cliente que realizou o download
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
