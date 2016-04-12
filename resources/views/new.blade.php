@extends('layouts.master')

@section('title', 'Benutzer hinzufügen')

@section('content')
    <form action="{{ ir_url('user/create') }}" method="post">
        <div class="title">
            <h1>Benutzer hinzufügen</h1>
        </div>
        @include('partials/properties')
    </form>
@endsection