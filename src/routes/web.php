<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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


Route::get('/', [ContactController::class, 'index']);
//ユーザーがブラウザで / にアクセスすると、Laravel はContactController@index を呼び出して処理を行います
Route::post('/confirm', [ContactController::class, 'confirm']);
//ユーザーがフォームに入力して「確認画面へ」ボタンを押すと、このルートが呼ばれます。
Route::post('/thanks', [ContactController::class, 'store']);
//ユーザーが確認画面で「送信」ボタンを押すと、このルートが呼ばれます

Route::middleware('auth')->group(function () {
    //この中のルートにアクセスするには ログインしていること が必要
    //未ログインの人はアクセスできない
    Route::get('/admin', [ContactController::class, 'admin']);
    //管理者用ページを表示する
    Route::get('/search', [ContactController::class, 'search']);
    //お問い合わせの検索ページ
    Route::post('/delete', [ContactController::class, 'destroy']);
    //お問い合わせを削除する
    Route::post('/export', [ContactController::class, 'export']);
    //お問い合わせデータを CSVなどに出力 する
});