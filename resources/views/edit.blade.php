@extends('layouts.master')

@section('title', 'Benutzer bearbeiten')

@section('content')
    <form action="{{ url('user/update', ['username' => $user->getUsername()]) }}" method="post">
        <div class="title">
            <h1>Benutzer bearbeiten</h1>
        </div>
        @include('partials/properties', ['username' => $user->getUsername()])
    </form>

    <div class="row">
        <div class="col-xs-3 col-xs-offset-9">
            <a class="btn btn-danger btn-block" href="{{ url('user/delete', ['user' => $user->getUsername()]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i> Löschen</a>
        </div>
    </div>

@endsection