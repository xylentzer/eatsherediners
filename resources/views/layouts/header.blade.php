<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eatshere Diner</title>

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>

<body class="bg-gray-100">

  <!-- Navbar -->
  <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">

      <!-- Logo -->
      <a href="{{ url('/') }}">
        <img src="{{ asset('storage/eatsherelogo.jpg') }}"
          alt="Logo"
          class="h-16 w-auto">
      </a>

      <!-- Right Side -->
      <div class="flex items-center space-x-8">

        <!-- Navigation Links -->
        <ul class="flex items-center space-x-10 text-gray-700 font-medium">

          <li>
            <a href="{{ url('/home') }}"
              class="font-bold transition {{ request()->is('home') ? 'text-red-700' : 'hover:text-red-700' }}">
              Home
            </a>
          </li>
          <li>
            <a href="{{ url('/test') }}"
              class="font-bold transition {{ request()->is('test') ? 'text-red-700' : 'hover:text-red-700' }}">
              Menu
            </a>
          </li>
          <li>
            <a href="{{ url('/contact') }}"
              class="font-bold transition {{ request()->is('contact') ? 'text-red-700' : 'hover:text-red-700' }}">
              Contact
            </a>
          </li>
          <li>
            <a href="{{ url('/about') }}"
              class="font-bold transition {{ request()->is('about') ? 'text-red-700' : 'hover:text-red-700' }}">
              About
            </a>
          </li>
           <li>
            <a href="{{ url('/track') }}"
              class="font-bold transition {{ request()->is('about') ? 'text-red-700' : 'hover:text-red-700' }}">
              Order Track
            </a>
          </li>

        </ul>

        <!-- Shopping Cart -->
        <a href="{{ route('cart') }}"
          class="relative text-gray-700 hover:text-red-600 transition duration-300 hover:scale-110">

          <!-- Cart SVG Icon -->
          <svg xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-8 h-8">

            <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M2.25 3h1.386a.75.75 0 01.73.602L5.61 9.75m0 0h11.64a.75.75 0 00.73-.602l1.2-5.398H5.61zm0 0L7.5 18h9.75M9 21a.75.75 0 100-1.5A.75.75 0 009 21zm9 0a.75.75 0 100-1.5A.75.75 0 0018 21z" />

          </svg>

          <!-- Cart Counter -->
          @php
          $cartCount = session('cart_count', 0);
          @endphp

          @if($cartCount > 0)
          <span
            class="absolute -top-2 -right-2
                                   w-5 h-5
                                   rounded-full
                                   bg-red-600
                                   text-white
                                   text-[10px]
                                   font-bold
                                   flex
                                   items-center
                                   justify-center">

            {{ $cartCount }}

          </span>
          @endif

        </a>

      </div>

    </div>
  </nav>

  <!-- Prevent content from hiding under navbar -->
  <div class="pt-24"></div>