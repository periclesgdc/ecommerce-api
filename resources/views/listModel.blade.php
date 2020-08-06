@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __($title) }}</div>

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
                @if (isset($actions['new']))
                    <div class="row mb-3 ml-1">
                        <a href="{{ $actions['new'] }}" class="btn btn-success">Novo</a>
                    </div>
                @endif
                @if (isset($table['rows']))
                	<table class="table table-bordered table-hover">
                		<thead>
                			<tr>
                            @foreach ($table['headers'] as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                			</tr>
                		</thead>
	                	<tbody>
	                	@foreach ($table['rows'] as $row)
	                		<tr>
                            @foreach ($row['fields'] as $field)
                                <td>{{ $field }}</td>
                            @endforeach
                            @if (isset($row['actions']))
	                			<td>
                					<a class="btn btn-primary p-1" href="{{ $row['actions']['changeURI'] }}">Atualizar</a>
            						<a class="btn btn-danger p-1" href="{{ $row['actions']['deleteURI'] }}">Excluir</a>
	                			</td>
                            @endif
	                		</tr>
	                	@endforeach
	                	</tbody>
                	</table>
            	@else
                    <h3>Nenhum registro encontrado</h3>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection