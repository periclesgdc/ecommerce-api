<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $table = 'tb_pedidos_produtos';
    protected $fillable = [
        'pedido_id',
        'produto_id'
    ];

    function pedido()
    {
        return $this->belongsTo('App\Pedido');
    }

    function produto()
    {
        return $this->belongsTo('App\Produto');
    }
}
