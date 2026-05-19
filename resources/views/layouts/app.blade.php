<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-body-200 antialiased min-h-screen">

  {{-- The navbar with `sticky` and `full-width` --}}
  <x-nav sticky full-width>

    <x-slot:brand>
      {{-- Drawer toggle for "main-drawer" --}}
      <label for="main-drawer" class="lg:hidden mr-3">
        <x-icon name="o-bars-3" class="cursor-pointer" />
      </label>

      {{-- Brand --}}
      <div>App</div>
    </x-slot:brand>

    {{-- Right side actions --}}
    <x-slot:actions>
      <x-icon
        name="tabler.bell"
        class="w-6 h-6 cursor-pointer"
        />
      <x-dropdown>
        <x-slot:trigger>
          <img
            src="https://randomuser.me/api/portraits/lego/1.jpg"
            class="w-8 h-8 rounded-full mr-2"
            />
        </x-slot:trigger>

        <x-menu-item
          icon="tabler.user"
          title="Mi Pefil"
          />
        <x-menu-item
          icon="tabler.logout"
          title="Salir del sistema"
          />
      </x-dropdown>
    </x-slot:actions>
  </x-nav>

  {{-- The main content with `full-width` --}}
  <x-main with-nav full-width>

    {{-- This is a sidebar that works also as a drawer on small screens --}}
    {{-- Notice the `main-drawer` reference here --}}
    <x-slot:sidebar
      drawer="main-drawer"
      collapsible
      class="bg-base-100"
      >

      {{-- User --}}
      @if($user = auth()->user())
        <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
          <x-slot:actions>
            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
          </x-slot:actions>
        </x-list-item>

        <x-menu-separator />
      @endif

      {{-- Activates the menu item when a route matches the `link` property --}}
      <x-menu activate-by-route>
        <x-menu-item
          title="Inicio"
          icon="tabler.home"
          link="{{ route('dashboard') }}"
          />

        @if ($user && ($user->hasRole('admin') || $user->hasRole('super-admin')))
          <x-menu-separator />
          <x-menu-item
            title="Admininstrar Usuarios"
            icon="tabler.users-group"
            link="{{ route('admin.users.index') }}"
            />
        @endif
      </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content class="bg-base-200">
      <div class="bg-base-100 shadow-md rounded-lg p-2">
        {{ $slot }}
      </div>
    </x-slot:content>
  </x-main>

  {{--  TOAST area --}}
  <x-toast />
</body>

</html>
