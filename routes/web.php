<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
Route::livewire('/ui', 'pages::ui');

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});
Route::livewire('/password', 'pages::auth.password')->name('password');
Route::livewire('/google-callback', 'pages::auth.callback');

Route::middleware(['auth'])->group(function () {
  Route::livewire('/admin/users', 'pages::admin.users.index')->name('admin.users.index');
});