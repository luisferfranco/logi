<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/ui', 'pages::ui');

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::get('/login-google', function () {
  if (auth()->check()) {
    return redirect()->route('dashboard');
  }
  return Socialite::driver('google')->redirect();
});
Route::livewire('/password', 'pages::auth.password')->name('password');
Route::livewire('/google-callback', 'pages::auth.callback');
Route::livewire('/invitacion/{codigo}', 'pages::auth.invitacion')->name('invitacion');
Route::livewire('/recover', 'pages::auth.recover')->name('password.request');
Route::livewire('/reinicio/{token}', 'pages::auth.reinicio')->name('password.reset');

Route::middleware(['auth'])->group(function () {
  // Generales ---------------------------------------------------------
  Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
  Route::livewire('/construccion', 'pages::construccion')->name('construccion');

  // Administración de usuarios ----------------------------------------
  Route::livewire('/admin/users', 'pages::admin.users.index')->name('admin.users.index');
  Route::livewire('/admin/users/create', 'pages::admin.users.create')->name('admin.users.create');
  Route::livewire('/admin/users/edit/{user?}', 'pages::admin.users.create')->name('admin.users.edit');

  // Rutas de administración -------------------------------------------
  Route::livewire('/admin/clientes', 'pages::admin.clientes.index')->name('admin.clientes.index');
  Route::livewire('/admin/clientes/create', 'pages::admin.clientes.create')->name('admin.clientes.create');
  Route::livewire('/admin/roles', 'pages::admin.roles.index')->name('admin.roles.index');
  Route::livewire('/admin/roles/create', 'pages::admin.roles.create')->name('admin.roles.create');
  Route::livewire('/admin/roles/{role}', 'pages::admin.roles.edit')->name('admin.roles.edit');
});
