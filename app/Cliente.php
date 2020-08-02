<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'ultima_atualizacao';

    protected $table = 'tb_clientes';
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco'
    ];
}
