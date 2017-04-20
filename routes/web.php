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

//Route::post('/hooks', 'DeploymentController@deploy');
Route::post('/hooks', function () {
    $commands = ['cd /html/wechat', 'git pull'];
    $headers = getallheaders();
    if ('sha1=' . hash_hmac('sha1', file_get_contents('php://input'), env('WEBHOOKS_SECRET'), false) === $_SERVER['HTTP_X_HUB_SIGNATURE']) {
        foreach ($commands as $command) {
            shell_exec($command);
        }
        http_response_code(200);
    } else {
        abort(403);
    }
});
