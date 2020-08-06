@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __($title) }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-10 offset-1">
                            <form action="{{ $form['action'] }}" method="{{ $form['method'] }}">
                                @csrf
                            @foreach($form['fields'] as $field)
                                <div class="form-group">
                                @if (isset($field['label']))
                                    <label>{{ $field['label'] }}</label>
                                @endif
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="{{ $field['class'] }}" value="{{ $field['value'] }}">
                                </div>
                            @endforeach
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
