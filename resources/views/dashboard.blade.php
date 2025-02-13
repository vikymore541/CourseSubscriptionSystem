<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h3>Welcome, {{ auth()->user()->name }}</h3>
@endsection
