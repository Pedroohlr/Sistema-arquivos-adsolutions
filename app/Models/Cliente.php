<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Authenticatable
{
  use Notifiable;

  protected $table = 'clientes';

  protected $fillable = [
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
   * Subpastas que este cliente tem acesso
   */
  public function subpastas(): BelongsToMany
  {
    return $this->belongsToMany(Subpasta::class, 'subpasta_cliente');
  }

  /**
   * Downloads realizados por este cliente
   */
  public function downloads(): HasMany
  {
    return $this->hasMany(Download::class);
  }
}
