<?php
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome');

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::middleware('auth')->group(function () {
  Route::livewire('/profile', 'pages::profile')->name('profile');
  Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
});