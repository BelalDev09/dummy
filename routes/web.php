<?php

use App\Http\Controllers\Web\backend\admin\CartManageController;
use App\Http\Controllers\Web\backend\CMS\HomePage\HomePageController;
use App\Http\Controllers\Web\backend\DashboardController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Public
 */

Route::get('/', fn() => view('welcome'));
Route::middleware(['auth'])->get('/dashboard', function () {

    if (auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
        return redirect()->route('admin.dashboard');
    }

    return abort(403, 'Only admin can access dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/carts',                     [CartManageController::class, 'index'])->name('carts.index');
    Route::get('/carts/export',              [CartManageController::class, 'export'])->name('carts.export');
    Route::get('/carts/{cart}',              [CartManageController::class, 'show'])->name('carts.show');
    Route::patch('/carts/{cart}/status',     [CartManageController::class, 'updateStatus'])->name('carts.updateStatus');
    Route::delete('/carts/{cart}',           [CartManageController::class, 'destroy'])->name('carts.destroy');

    /**
     *
     * CMS Pages
     */
    Route::prefix('cms')->name('cms.')->group(function () {
        // category by product

        Route::get('/products-by-category', [HomePageController::class, 'getProductsByCategory'])
            ->name('products_by_category');
        // Home Page
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/top-section', 'topSection')->name('top_section');
            Route::patch('/top-section/update', 'topSectionUpdate')->name('top_section.update');
        });
        // Home Page Category Section
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/category-section', 'categorySection')->name('category_section');
            Route::patch('/category-section/update', 'categorySectionUpdate')->name('category_section.update');
        });
        // home page men collection section
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/men-collection-section', 'menCollectionSection')->name('men_collection_section');
            Route::patch('/men-collection-section/update', 'menCollectionSectionUpdate')->name('men_collection.update');
        });
        // home page women collection section
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/women-collection-section', 'WomenCollectionSection')->name('women_collection_section');
            Route::patch('/women-collection-section/update', 'WomenCollectionSectionUpdate')->name('women_collection.update');
        });
        //watches section
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/watches-section', 'watchesSection')->name('watches_section');
            Route::patch('/watches-section/update', 'watchesSectionUpdate')->name('watches.update');
        });
        //high tech section
        Route::controller(HomePageController::class)->prefix('home-page')->name('home_page.')->group(function () {
            Route::get('/high-tech-section', 'HighTechSection')->name('high_tech_section');
            Route::patch('/high-tech-section/update', 'HighTechSectionUpdate')->name('high_tech.update');
        });
    });
});

require __DIR__ . '/auth.php';
