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
    return 'Hello World';
});

Route::group(['prefix' => 'wechat'], function () {

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

});

Route::any('hooks', function () {
    $secret = env('WEBHOOKS_SECRET');
    $path = env('WEBHOOKS_PATH');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
    if ($signature) {
        $hash = "sha1=" . hash_hmac('sha1', file_get_contents("php://input"), $secret);
        if (strcmp($signature, $hash) == 0) {
            $cmd = "cd {$path} && /usr/bin/git reset --hard origin/master && /usr/bin/git clean -f && /usr/bin/git pull 2>&1";
            echo shell_exec($cmd);
            exit();
        }
    }
    http_response_code(500);
});
