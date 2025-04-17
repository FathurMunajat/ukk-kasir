<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts & Icons -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

  @yield('head')
</head>
<body class="bg-gray-100">
  <div id="app" class="flex">
    @auth
    <!-- Sidebar -->
    <aside class="w-64 h-screen fixed top-0 left-0 bg-white border-r shadow">
      <div class="p-4 flex items-center gap-3 border-b">
        <img src="{{ asset('storage/images/icon.jpg') }}" alt="Logo" class="w-10 h-10 rounded-full">
        <span class="text-xl font-semibold text-gray-800">Kasir Drone</span>
      </div>

      
      <nav class="mt-4 space-y-1 px-4">
        <a href="{{ url('/Dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100 text-gray-800">
          <i class="bi bi-house-door-fill"></i> Dashboard
        </a>
      

        <a href="{{ url('/Product') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100 text-gray-800">
          <i class="bi bi-bag-fill"></i> Produk
        </a>
      
      
        
        <a href="{{ url('/Purchase') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100 text-gray-800">
          <i class="bi bi-cart-fill"></i> Pembelian
        </a>
        
  
        
        <a href="{{ url('/User') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100 text-gray-800">
          <i class="bi bi-person-fill"></i> User
        </a>
       

      </nav>
    </aside>
    @endauth

    <!-- Main Content -->
    <div class="@auth ml-64 @endauth flex-1 min-h-screen flex flex-col">
      <!-- Navbar -->
      <nav class="w-full bg-white shadow px-4 py-2 flex justify-between items-center">
        <div class="text-lg font-semibold">Halo, {{ Auth::user()->name ?? 'Tamu' }}</div>
        @auth
        <div class="flex items-center gap-3">
          <span class="text-sm text-gray-600">{{ Auth::user()->email }}</span>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-500 hover:underline">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        </div>
        @endauth
      </nav>

      <!-- Page Content -->
      <main class="p-4">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
  @yield('scripts')
</body>
</html>
