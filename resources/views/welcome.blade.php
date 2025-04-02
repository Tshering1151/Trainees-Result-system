@extends('components.layout')

@section('title')
welcome
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('header')
Student Result Management System
@endsection

@section('content')
<div class="container"> 
    <!-- Trainee Section -->
    <div class="card trainee">
        <h3>For Trainees</h3>
        <p>Student Result Management System</p>
        <p><strong>Search your result</strong> <a href="{{ route('trainee.result') }}">click here</a></p>
    </div>

    <!-- Admin Login Section -->
    <div class="card admin">
        <h3>Admin Login</h3>
        <p>Student Result Management System</p>
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="button-container">
                <button type="submit">Sign in ✔</button>
            </div>
        </form>
    </div>
</div>
@endsection