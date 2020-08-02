<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'ultima_atualizacao';

    protected $table = 'tb_pedidos';
    protected $fillable = [
        'cliente_id',
        'status_id'
    ];

    protected $attributes = [
        'status_id' => env('STATUS_ID_PEDIDO_DEFAULT')
    ];

    function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    function produtos()
    {
      return $this->hasMany('App\Produto');
    }
}
