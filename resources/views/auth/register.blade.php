@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="70" class="mb-2 rounded-circle shadow">
            <h2 class="h4 fw-bold mb-0">Dagupan City National Highschool</h2>
            <div class="text-primary mb-2">Library Management System</div>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-input-label for="student_id" :value="__('Student ID')" />
                <x-text-input id="student_id" class="form-control" type="text" name="student_id" :value="old('student_id')" required autocomplete="student-id" />
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">{{ __('Register') }}</button>
        </form>
        <div class="mt-4 text-center">
            <span class="text-muted">Already registered?</span>
            <a href="{{ route('login') }}" class="text-primary fw-semibold">Log in</a>
        </div>
    </div>
</div>
@endsection
