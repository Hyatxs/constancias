@extends('template')
@section('title','home')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">

@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Menu') }}</div>
                <div class="list-group">
                    @foreach ($modulos as $modulo)
                        <a href="{{ route($modulo->enlace . '.index') }}" class="list-group-item list-group-item-action">
                            {{ $modulo->nombre }}
                            <i class="{{ $modulo->icono }}"></i>
                        </a>
                    @endforeach
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest" type="text/javascript"></script>
    <script src="{{asset('js/datatables-simple-demo.js')}}"></script>

@endpush
