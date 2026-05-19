<?php
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome');

Route::livewire('/login', 'pages::auth.login')->name('login');