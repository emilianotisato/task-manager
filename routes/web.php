<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Volt::route('projects', 'projects.index')->name('projects.index');
    Volt::route('projects/create', 'projects.form')->name('projects.create');

    Volt::route('tasks/create', 'tasks.create')->name('tasks.create');
    Volt::route('tasks/{task}/edit', 'tasks.edit')->name('tasks.edit');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
