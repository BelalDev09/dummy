<?php

use App\Http\Controllers\API\App\V1\NotificationController;
use App\Http\Controllers\API\cms\CMSHomePageController;
use App\Http\Controllers\API\V1\CartApiController;
use App\Http\Controllers\API\V1\CartController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\NewsletterController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\TermsAndConditionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('cart')->group(function () {
        Route::post('/add', [CartApiController::class, 'add']);
        Route::get('/', [CartApiController::class, 'index']);
        Route::patch('/update', [CartApiController::class, 'update']);
        // Route::match(['post', 'patch'], '/update', [CartApiController::class, 'update']);
        Route::delete('/remove', [CartApiController::class, 'remove']);

        Route::post('/checkout', [CartApiController::class, 'checkout']);
    });


    // newsletter api
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'store']);
    Route::get('/newsletters', [NewsletterController::class, 'index']);
});
// modeule wise routes
Route::get('categories', [CategoryController::class, 'index']);
Route::get('sub-categories', [CategoryController::class, 'subCategories']);
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('cms')
    ->group(function () {
        Route::get('/home-page/top-section', [CMSHomePageController::class, 'topSection']);
        Route::get('/home-page/category-section', [CMSHomePageController::class, 'categorySection']);
        Route::get('/home-page/men-collection-section', [CMSHomePageController::class, 'menCollectionSection']);
        Route::get('/home-page/women-collection-section', [CMSHomePageController::class, 'womenCollectionSection']);
        Route::get('/home-page/watches-collection-section', [CMSHomePageController::class, 'watchesCollectionSection']);
        Route::get('/home-page/high-tech-section', [CMSHomePageController::class, 'highTechCollectionSection']);
    });

// faq
Route::get('/terms', [TermsAndConditionController::class, 'terms']);
Route::get('/faqs', [TermsAndConditionController::class, 'faqs']);
Route::middleware(['jwt.verify'])
    ->group(function () {
        // faq and terms
        Route::get('/social-icon', [TermsAndConditionController::class, 'socialicon']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
    });
