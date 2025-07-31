@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        {{-- Input Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
            
            {{-- Menampilkan error spesifik untuk email --}}
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Input Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required>
            
            {{-- Menampilkan error spesifik untuk password --}}
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('register') }}">Don't have an account?</a>
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>
    </form>
@endsection