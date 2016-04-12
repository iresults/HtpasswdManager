@extends('layouts.master')

@section('title', 'Page Title')

@section('content')
    @if($user)
        {{ $user->getUsername() }} {{ $user->getEncryptedPassword() }}
    @endif

    <p>This is appended to the master content.</p>
@endsection