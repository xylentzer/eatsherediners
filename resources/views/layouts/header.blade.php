<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eatshere Diner</title>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif

<body class="bg-gray-100">
  <!-- Navbar -->
  <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6  flex justify-between items-center">
      <!-- Logo or Brand -->
      <a href="{{ url('/') }}"> <img src="{{ asset('storage/eatsherelogo.jpg') }}" alt="Logo" class="h-20 w-35"></a>

      <!-- Navigation Links -->
      <ul class="flex space-x-10 text-gray-700 font-medium">
        <li><a href="{{ url('/home') }}" class="hover:text-red-700 font-bold transition">Home</a></li>
        <li><a href="{{ url('/service') }}" class="hover:text-red-700 font-bold transition">Services</a></li>
        <li><a href="{{ url('/menu') }}" class="hover:text-red-700 font-bold transition">Menu</a></li>
        <li><a href="{{ url('/contact') }}" class="hover:text-red-700 font-bold transition">Contact</a></li>
        <li><a href="{{ url('/about') }}" class="hover:text-red-700 font-bold transition">About</a></li>
      </ul>
    </div>
  </nav>