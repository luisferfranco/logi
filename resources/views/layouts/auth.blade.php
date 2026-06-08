<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
  </head>
  <body class="min-h-screen font-sans antialiased bg-base-200 flex items-center justify-center">

    <x-card
      class="relative bg-base-100 shadow-xl w-full md:w-2xl md:mx-auto md:max-w-2xl"
      separator
      >
      <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-base-100 p-4 rounded-xl">
        <a wire:navigate href="{{ route('home') }}">
          <img src="/img/ferti-v.svg" alt="Fertinal" class="w-24" />
        </a>
      </div>

      <div class="mt-6">
        {{ $slot }}
      </div>

    </x-card>

    @livewireScripts
  </body>
</html>
