@extends('layouts.master')

@section('title', 'Benutzerliste')

@section('content')
    <ul class="user-list">
        @foreach ($users as $user)
            <li>
                <a class="btn btn-secondary btn-block" href="{{ ir_url('user/edit', ['user' => $user->getUsername()]) }}"><i class="fa fa-user"></i> {{ $user->getUsername() }}</a>
            </li>
        @endforeach
    </ul>

    <a href="{{ ir_url('user/new') }}" class="btn btn-secondary btn-block">Benutzer hinzufügen</a>
@endsection