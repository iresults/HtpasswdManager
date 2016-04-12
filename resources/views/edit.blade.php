@extends('layouts.master')

@section('title', 'Benutzer bearbeiten')

@section('content')
    <form action="{{ ir_url('user/update', ['username' => $user->getUsername()]) }}" method="post">
        <div class="title">
            <h1>Benutzer bearbeiten</h1>
        </div>
        @include('partials/properties', ['username' => $user->getUsername()])
    </form>

    <div class="row">
        <div class="col-md-3 col-md-offset-2">
            <a class="btn btn-secondary btn-block" href="{{ ir_url('users') }}">Benutzerliste</a>
        </div>
        <div class="col-md-3 col-md-offset-4">
            <a class="btn btn-danger btn-block" href="{{ ir_url('user/delete', ['user' => $user->getUsername()]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i> Löschen</a>
        </div>
    </div>

@endsection