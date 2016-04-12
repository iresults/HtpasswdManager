@extends('layouts.master')

@section('title', 'New')

@section('content')
    <form action="{{ url('user/create') }}" method="post">
        <div class="title">
            <h1>Benutzer hinzufügen</h1>
        </div>
        @include('partials/properties')
    </form>
@endsection