<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/ui', 'pages::ui');

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});
Route::livewire('/password', 'pages::auth.password')->name('password');
Route::livewire('/google-callback', 'pages::auth.callback');
Route::livewire('/invitacion/{codigo}', 'pages::auth.invitacion')->name('invitacion');
Route::livewire('/recover', 'pages::auth.recover')->name('password.request');
Route::livewire('/reinicio/{token}', 'pages::auth.reinicio')->name('password.reset');

Route::middleware(['auth'])->group(function () {
  Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

  Route::livewire('/admin/users', 'pages::admin.users.index')->name('admin.users.index');
  Route::livewire('/admin/users/create', 'pages::admin.users.create')->name('admin.users.create');
});
