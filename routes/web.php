<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Categories;
use App\Http\Livewire\Tags;
use App\Http\Livewire\Posts;
use App\Http\Livewire\PostDetail;
use App\Http\Livewire\Contact;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/submit', [HomeController::class, 'submit'])->name('submit');
Route::post('/contact', [HomeController::class, 'postContact'])->name('contact');

Route::group(['prefix' => '', 'middleware' => [], 'as' => 'web.'], function() {
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/tags', Tags::class)->name('tags');
    Route::get('/posts', Posts::class)->name('posts');
    Route::get('/post/{slug}', PostDetail::class)->name('post-detail');
    Route::get('/contact', Contact::class)->name('contact');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
