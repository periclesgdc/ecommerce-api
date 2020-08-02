<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'ultima_atualizacao';

    protected $table = 'tb_produtos';
    protected $fillable = [
        'nome',
        'preco',
        'pedido_id'
    ];

    function pedido()
    {
        return $this->belongsTo('App\Pedido');
    }
}
