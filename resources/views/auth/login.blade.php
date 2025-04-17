@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
  <div class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
    <form class="space-y-6" method="POST" action="{{ route('login') }}">
      @csrf

      <h5 class="text-xl font-semibold text-gray-800 text-center">Login ke Akun Anda</h5>

      <div>
        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
        <input id="email" type="email" name="email"
          class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
          placeholder="email@example.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
        <input id="password" type="password" name="password"
          class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
          placeholder="••••••••" required autocomplete="current-password">
        @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember" type="checkbox" name="remember"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
            {{ old('remember') ? 'checked' : '' }}>
          <label for="remember" class="ml-2 text-sm font-medium text-gray-700">Ingat saya</label>
        </div>

        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
        @endif
      </div>

      <button type="submit"
        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Masuk
      </button>
    </form>
  </div>
</div>
@endsection
