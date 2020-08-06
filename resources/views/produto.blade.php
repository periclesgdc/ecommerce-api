@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Produtos') }}</div>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-body">
                	<table class="table table-bordered table-hover">
                		<thead>
                			<tr>
                				<th>ID</th> <th>Nome</th> <th>Preço</th> <th>Ações</th>
                			</tr>
                		</thead>
	                	<tbody>
	                	@foreach ($produtos as $produto)
	                		<tr id="produto-{{ $produto->id }}">
	                			<th>{{ $produto->id }}</th>
	                			<td>{{ $produto->nome }}</td>
	                			<td>R$ {{ $produto->preco }}</td>
	                			<td>
                					<a class="btn btn-primary p-1" href="/produtos/alterar/{{ $produto->id }}">Atualizar</a>
            						<a class="btn btn-danger p-1" href="/produtos/deletar/{{ $produto->id }}">Excluir</a>
	                			</td>
	                		</tr>
	                	@endforeach
	                	</tbody>
                	</table>
            	</div>
            </div>
        </div>
    </div>
</div>

@endsection