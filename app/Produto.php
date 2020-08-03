<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'tb_produtos';
    protected $fillable = [
        'nome',
        'preco'
    ];
}
