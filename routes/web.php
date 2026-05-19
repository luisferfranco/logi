<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome');

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::middleware('auth')->group(function () {
    Route::livewire('/profile', 'pages::profile')->name('profile');
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

    Route::livewire('/admin/users', 'pages::admin.users.index')
        ->name('admin.users.index');
    Route::livewire('/admin/users/create', 'pages::admin.users.create')
        ->name('admin.users.create');

    Route::livewire('/admin/empresas', 'pages::admin.empresas.index')
        ->name('admin.empresas.index');

    Route::livewire('/ui', 'pages::ui')->name('ui');
});
