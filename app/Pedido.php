<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'tb_pedidos';
    protected $fillable = [
        'cliente_id',
        'status_id'
    ];

    function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    function produtos()
    {
      return $this->hasMany('App\PedidoProduto');
    }
}
