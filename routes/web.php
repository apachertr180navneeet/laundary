<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\backends\{
    HomeController,
    AuthController
};
use App\Http\Controllers\Modules\{
    DashboardController,
    ClientController,
    OrderController,
    CategoryController,
    PaymentController,
    InvoiceController,
    ItemTypeController,
    CategoriesController,
    ServicesController,
    ItemController
};

// ============================================
// PUBLIC WEBSITE
// ============================================
Route::get('/', function () {
    return view('web.home');
})->name('home');

// ============================================
// ERP SYSTEM
// ============================================
Route::prefix('erp')->group(function () {

    // Auth routes (login, forgot password, etc.)
    require __DIR__ . '/auth.php';

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    // Authenticated ERP routes
    Route::middleware('auth')->group(function () {

        // Profile management
        Route::controller(HomeController::class)->group(function () {
            Route::get('/myProfile', 'myprofile')->name('myProfile');
            Route::get('/edit/profile/{id}', 'editprofile')->name('edit.profile');
            Route::post('/profile/update/{id}', 'updateprofilepost');
        });

        // Password management
        Route::controller(AuthController::class)->group(function () {
            Route::get('/change/password', 'changePassword')->name('change.password');
            Route::post('/change/password/post', 'changePasswordPost')->name('change.password.post');
        });

        // Users
        Route::resource('users', UserController::class);

        // Clients
        Route::controller(ClientController::class)->group(function () {
            Route::get('/client', 'index')->name('clientpage');
            Route::post('/add-client', 'addClient')->name('add.client');
            Route::post('/edit-client/{id}', 'editClient');
            Route::get('/delete-client/{id}', 'deleteClient');
        });

        // Categories (CategoriesController - frontend categories)
        Route::controller(CategoriesController::class)->group(function () {
            Route::get('/categories', 'index')->name('categories');
            Route::get('/categories/{id}/edit', 'edit')->name('categories.edit');
            Route::post('/add-category', 'addCategory')->name('add.category');
            Route::post('/edit-category/{id}', 'editCategory')->name('edit.category');
            Route::get('/delete-category/{id}', 'deleteCategory')->name('delete.category');
            Route::get('/categories/search', 'search')->name('search.categories');
        });

        // Services
        Route::controller(ServicesController::class)->group(function () {
            Route::get('/services', 'index')->name('services');
            Route::get('/services/{id}/edit', 'edit')->name('services.edit');
            Route::post('/add-services', 'addServices')->name('add.services');
            Route::post('/edit-services/{id}', 'editServices')->name('edit.services');
            Route::get('/delete-services/{id}', 'deleteServices')->name('delete.services');
            Route::get('/services/search', 'search')->name('search.services');
        });

        // Items
        Route::controller(ItemController::class)->group(function () {
            Route::get('/items', 'index')->name('items');
            Route::get('/itemsitems/{id}/edit', 'edit')->name('items.edit');
            Route::get('/add-items', 'addItems')->name('add.items');
            Route::post('/add-store', 'storeItems')->name('store.item');
            Route::get('/edit-items/{id}', 'editItem')->name('edit.items');
            Route::post('/update-store', 'updateItems')->name('update.item');
            Route::get('/delete-items/{id}', 'deleteItem')->name('delete.items');
            Route::get('/items/search', 'search')->name('search.items');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/order', 'index')->name('addOrder');
            Route::post('/add-order', 'addOrder')->name('add.order');
            Route::post('/get-service', 'getServiceData');
            Route::post('/get-allservice', 'getAllServiceData');
            Route::get('edit-order/{id}', 'editOrder')->name('order.edit');
            Route::post('update-order/{id}', 'updateOrder')->name('order.update');
            Route::get('/view-order', 'viewOrder')->name('viewOrder');
            Route::get('/show-order/{orderId}', 'OrderDetail')->name('OrderDetail');
            Route::get('/delete-order/{id}', 'deleteOrder');
            Route::get('/receipt/{orderId}', 'PrintReceipt')->name('receipt');
            Route::get('/invoice/{orderId}', 'PrintInvoice')->name('invoicepdf');
            Route::get('/tagslist/{orderId}', 'tagList')->name('tagslist');
            Route::get('/print-taglist/{orderId}', 'printTaglist')->name('download-tagslist');
            Route::match(['get', 'post'], '/send-wh-message/{orderId}', 'sendWhMessage')->name('orders.store');
            Route::get('/fetch-client-name', 'fetchClientName');
            Route::get('/download-receipt/{orderId}', 'downloadReceipt')->name('download-receipt');
            Route::get('/download-invoice/{orderId}', 'downloadInvoice')->name('download-invoice');
            Route::get('/get-services', 'getServices')->name('getServices');
            Route::get('/get-price', 'getPrice')->name('getprice');
            Route::get('/receipt-print/{orderId}', 'RecieptPrint')->name('receipt-print');
            Route::get('/invoice-print/{orderId}', 'InvoicePrint')->name('invoice-print');
        });

        // Category (CategoryController - laundry categories)
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categorylist', 'index')->name('categorylist');
            Route::get('/category', 'addcategory')->name('category');
            Route::post('/category-add', 'storeCategory')->name('add.category.details');
            Route::get('/fetch-data-clothes', 'fetchClothesData');
            Route::get('/fetch-data-upholstrey', 'fetchUpholsteryData');
            Route::get('/fetch-data-footbags', 'fetchFootBagData');
            Route::get('/fetch-data-other', 'fetchOtherData');
            Route::get('/fetch-data-laundry', 'fetchLaundryData');
            Route::post('/delete-clothes/{id}', 'deleteClothes');
            Route::post('/categorylist', 'editItems');
        });

        // Item Types
        Route::controller(ItemTypeController::class)->group(function () {
            Route::get('/itemtype', 'index')->name('itemtype');
            Route::post('/add-itemtype', 'addType')->name('add.itemtype');
            Route::post('/edit-itemtype/{id}', 'updateItemType');
            Route::get('/delete-itemtype/{id}', 'deleteItemType');
        });

        // Payments
        Route::controller(PaymentController::class)->group(function () {
            Route::get('/payment', 'index')->name('payment');
            Route::post('/settle-and-deliver-order/{orderId}', 'settleAndDeliverOrder');
        });

        // Invoices
        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoice', 'index')->name('invoice');
            Route::get('/indexfilter', 'indexfilter')->name('indexfilter');
            Route::get('/orders/export', 'export')->name('orders.export');
        });
    });
});
