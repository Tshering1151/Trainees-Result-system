@extends('components.layout')

@section('title')
Admin Dashboard
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/styleHome.css') }}">
@endsection

@section('content')
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">×</span>
    <a href="#" class="active">Dashboard</a>
    <a href="{{url('/coursedashboard')}}">Courses</a>
    <a href="{{url('/unit')}}">Units</a>
    <a href="{{url('/trainees')}}">Trainees</a>
    <a href="{{url('/result')}}">Results</a>
    <a href="#">Reports</a>
</aside>

<!-- Main Content -->
<div class="content">
    <button class="open-sidebar-btn" onclick="openSidebar()">☰ Open Sidebar</button>
</div>

<!-- Page Title -->
<h2 class="page-title">Trainees Result Management System</h2>

@endsection