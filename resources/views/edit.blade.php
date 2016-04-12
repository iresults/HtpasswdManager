@extends('layouts.master')

@section('title', 'Benutzer bearbeiten')

@section('content')
    <form action="{{ url('user/update', ['username' => $user->getUsername()]) }}" method="post">
        <div class="title">
            <h1>Benutzer bearbeiten</h1>
        </div>
        @include('partials/properties', ['username' => $user->getUsername()])
    </form>
@endsection