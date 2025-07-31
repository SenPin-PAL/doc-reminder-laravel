@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        {{-- Input Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        {{-- Input Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        {{-- Input Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" type="password" name="password" required>
        </div>

        {{-- Input Konfirmasi Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}">Already registered?</a>
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>
@endsection