@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="mr-2"><a href="/produtos" class="btn btn-primary">Ver Produtos</a></div>
                        <div class="mr-2"><a href="/clientes" class="btn btn-danger">Ver Clientes</a></div>
                        <div class=""><a href="/pedidos" class="btn btn-success">Ver Pedidos</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
