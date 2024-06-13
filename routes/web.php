<?php

use App\Livewire\Components\Logout;
use App\Livewire\Components\UserManagement\UserForm;
use App\Livewire\Pages\CashierPage;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\LoginPage;
use App\Livewire\Pages\UserManagementPage;
use Illuminate\Support\Facades\Route;

//login
Route::get('/', LoginPage::class)->name('login')->middleware('CheckIfLoggedIn');
Route::get('/logout', Logout::class)->name('logout');

//homepage
Route::get('/admin', HomePage::class)->name('admin.index')->middleware('RedirectIfLoggedIn');

Route::get('/admin/UserManagement', UserManagementPage::class)->name('usermanagement.index')->middleware('RedirectIfLoggedIn');

Route::get('/cashier', CashierPage::class)->name('cashier.index')->middleware('RedirectIfLoggedIn');
