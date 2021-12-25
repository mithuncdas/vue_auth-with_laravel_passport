<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['namespace' => 'Api','prefix' => 'v1'],function(){
    Route::post('/register','AuthController@register')->name('register');
    Route::post('/login','AuthController@login')->name('login');

    Route::get('/login',function(){
        return response()->json([
            'message' => 'Unauthorized Entry'
        ],401);
    })->name('login');

    Route::group(['middleware' => 'auth:api'],function(){
        Route::get('/user/all','AuthController@index')->name('all.user');
        Route::post('/logout','AuthController@logout')->name('logout');

        Route::get('/user/{id}','AuthController@show')->name('show.user');

        Route::get('/blog/all','BlogController@index')->name('blog.index');
        Route::post('/blog/store','BlogController@store')->name('blog.store');
        Route::get('/blog/{id}','BlogController@show')->name('blog.show');
        Route::put('/blog/update/{id}','BlogController@update')->name('blog.update');
        Route::get('/blog/destroy/{id}','BlogController@destroy')->name('blog.destroy');
    });

});

