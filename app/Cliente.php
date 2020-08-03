<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'tb_clientes';
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco'
    ];
}
