@extends('layouts.back-admin')

@section('title', 'BrebasMotors Admin')

@php
    $carsCount = \App\Models\Car::count();
    $usersCount = \App\Models\User::count();
@endphp

@section('content')
    @include('back.partials.stats', [
        'carsCount' => $carsCount,
        'usersCount' => $usersCount,
    ])
@endsection
