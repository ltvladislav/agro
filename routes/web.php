<?php

use Illuminate\Support\Facades\Route;

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

use Zirael\ZiraelBase\Test;

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    $namespacePrefix = 'WithItemsBase\\';
    Route::group([
        'as'     => 'voyager.infoblocks.',
        'prefix' => 'infoblocks/{master}',
    ], function () use ($namespacePrefix) {
        Route::get('builder', ['uses' => $namespacePrefix.'BaseWithItemsController@builder',    'as' => 'builder']);
        Route::post('order', ['uses' => $namespacePrefix.'BaseWithItemsController@order_item', 'as' => 'order']);

        Route::group([
            'as'     => 'item.',
            'prefix' => 'item',
        ], function () use ($namespacePrefix) {
            Route::delete('{id}', ['uses' => $namespacePrefix.'BaseWithItemsController@delete_item', 'as' => 'destroy']);
            Route::post('/', ['uses' => $namespacePrefix.'BaseWithItemsController@add_item',    'as' => 'add']);
        });
    });


    Route::group([
        'as'     => 'voyager.galleries.',
        'prefix' => 'galleries/{master}',
    ], function () use ($namespacePrefix) {
        Route::get('builder', ['uses' => $namespacePrefix.'BaseWithItemsController@builder',    'as' => 'builder']);
        Route::post('order', ['uses' => $namespacePrefix.'BaseWithItemsController@order_item', 'as' => 'order']);

        Route::group([
            'as'     => 'item.',
            'prefix' => 'item',
        ], function () use ($namespacePrefix) {
            Route::delete('{id}', ['uses' => $namespacePrefix.'BaseWithItemsController@delete_item', 'as' => 'destroy']);
            Route::post('/', ['uses' => $namespacePrefix.'BaseWithItemsController@add_item',    'as' => 'add']);
        });
    });
});



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function()
{
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function () {
        return view('index')->withShortcodes();
    });

    Auth::routes();
    /*Route::group([], function () {
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        // Registration Routes...
        //Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register')->name('register');
        // Password Reset Routes...
        //Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    });*/

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'requests', 'middleware' => ['auth']], function () {
        Route::get('/', 'Request\RequestController@index')->name('requests');
        Route::get('/create', 'Request\RequestController@create')->name('requests-create');
        Route::get('/{id}', 'Request\RequestController@show')->name('request');
        Route::post('/store', 'Request\RequestController@store')->name('requests.store');
    });

    Route::group([], function () {
        Route::get('/posts', 'Posts\PostController@publicIndex')->name('posts');
        Route::get('/post/{slug?}', 'Posts\PostController@publicShow')->name('post');
        Route::post('/post-service', 'Posts\PostController@postService')->name('post-service');
    });

    Route::get('services/plant-growing', function() {
        return view('services.livestock');
    });
    Route::get('services/livestock', function() {
        return view('services.livestock');
    });

    Route::get('{pageSlug}', 'Pages\PageController@show');

});

Route::post('/tables/tablesStructures', 'Tables\TableController@getStructure')->name('tablesGetStructure');
Route::post('/tables/database', 'Tables\TableController@getData')->name('tablesGetData');


Route::get('/test/{name}', function($sName) {
    $test = new Test();
    return $test->greet($sName);
});
