@extends('layout.employee_sidebar')

@section('title', 'Employee Dashboard - MUMS')

@section('content')
<style>
    .card {
        width: min(92vw, 720px);
        padding: 32px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
        text-align: center;
        margin: 24px auto;
    }

    .badge {
        display: inline-block;
        margin-bottom: 16px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #b45309;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .primary-button {
        display: inline-block;
        margin-top: 18px;
        padding: 12px 18px;
        border: none;
        border-radius: 12px;
        background: #0f172a;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<main class="card">
    <span class="badge">Employee</span>
    <h1>Dashboard Employee</h1>
    <p>Silakan buka Task Management untuk melihat task yang harus dikerjakan.</p>

    <a class="primary-button" href="{{ route('tasks.index') }}">Buka Task Management</a>
</main>
@endsection