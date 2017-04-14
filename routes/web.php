<?php

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

Route::get('/', function () {
    return redirect('http://larry666.com:8080');
});
Route::get('/tags/{tag}', function ($tag) {
    return redirect("http://larry666.com:8080/tags/$tag");
});
Route::get('/view/{post_id}', function ($post_id) {
    return redirect("http://larry666.com:8080/view/$post_id");
});

Route::any('callback', ['as' => 'wechat_callback', 'uses' => 'Wechat\IndexController@callback']);
Route::get('menu', 'Wechat\IndexController@menu');
Route::get('notice', 'Wechat\IndexController@notice');
Route::get('reply', 'Wechat\IndexController@reply');
Route::get('qrcode', 'Wechat\IndexController@qrCode');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        dd($user);
    });
});