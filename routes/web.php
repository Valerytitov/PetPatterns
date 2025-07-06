<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

/* Control */
Route::middleware(['auth'])->group(function () {
    Route::get('/control', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

    Route::get('/control/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/control/orders/create', [App\Http\Controllers\AdminOrderController::class, 'form'])->name('admin.orders.create');
    Route::get('/control/orders/form/{id}', [App\Http\Controllers\AdminOrderController::class, 'form'])->name('admin.orders.form');
    Route::post('/control/orders/form/{id}', [App\Http\Controllers\AdminOrderController::class, 'form']);
    Route::post('/control/orders/delete-mass', [App\Http\Controllers\AdminOrderController::class, 'deleteMass'])->name('admin.orders.delete_mass');

    Route::get('/control/vfiles', [App\Http\Controllers\AdminVfileController::class, 'index'])->name('admin.vfiles');
    Route::get('/control/vfiles/form/{id?}', [App\Http\Controllers\AdminVfileController::class, 'form'])->name('admin.vfiles.form');
    Route::post('/control/vfiles/form/{id}', [App\Http\Controllers\AdminVfileController::class, 'form'])->name('admin.vfiles.update');
    Route::post('/control/vfiles/form', [App\Http\Controllers\AdminVfileController::class, 'form'])->name('admin.vfiles.store');
    Route::match(['get', 'post'], '/control/vfiles/props/{id}', [App\Http\Controllers\AdminVfileController::class, 'props'])->name('admin.vfiles.props');

    Route::get('/control/posts', [App\Http\Controllers\AdminPostController::class, 'index'])->name('admin.posts');
    Route::match(['get', 'post'], '/control/posts/form/{id?}', [App\Http\Controllers\AdminPostController::class, 'form'])->name('admin.posts.form');

    Route::get('/control/reviews', [App\Http\Controllers\AdminReviewController::class, 'index'])->name('admin.reviews');
    Route::match(['get', 'post'], '/control/reviews/form/{id?}', [App\Http\Controllers\AdminReviewController::class, 'form'])->name('admin.reviews.form');

    Route::get('/control/delete/{type}/{id}', [App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');

    Route::get('/control/contacts', [App\Http\Controllers\AdminContactController::class, 'edit'])->name('admin.contacts.edit');
    Route::post('/control/contacts', [App\Http\Controllers\AdminContactController::class, 'update'])->name('admin.contacts.update');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/payment/test', [App\Http\Controllers\PaymentController::class, 'test'])->name('payment.test');
Route::match(['get', 'post'], '/payment/result', [App\Http\Controllers\PaymentController::class, 'result'])->name('payment.result');

Route::get('/constructor', [App\Http\Controllers\HomeController::class, 'constructor'])->name('constructor');
Route::get('/constructor/{id}', [App\Http\Controllers\HomeController::class, 'constructor_use'])->name('constructor.use');

Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');
Route::post('/order', [App\Http\Controllers\ShopController::class, 'order'])->name('shop.order');

Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'single'])->name('blog.single');

Route::get('/contacts', [\App\Http\Controllers\PageController::class, 'contacts'])->name('contacts');

Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'index'])->name('page');

// Маршрут для отображения страницы конкретной выкройки
Route::get('/patterns/{vfile:slug}', [\App\Http\Controllers\VfileController::class, 'show'])->name('vfiles.show');
Route::post('/patterns/{vfile:slug}/generate', [\App\Http\Controllers\VfileController::class, 'generatePdf'])->name('vfiles.generate');

Route::get('/test-flash', function() {
    return back()->with('success', 'Тестовое сообщение!');
});

// Оплата через Prodamus
Route::get('/shop/pay/{pattern}', [\App\Http\Controllers\ProdamusController::class, 'pay'])->name('shop.pay');

// Для бесплатных заказов: завершение генерации и success
Route::get('/payment/free/{order}', [\App\Http\Controllers\VfileController::class, 'freePaymentSuccess'])->name('payment.free.success');
