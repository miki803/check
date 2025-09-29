<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact'); // contact.blade.php を返す
    }
     // 確認画面
    public function confirm(Request $request)
    {
        return view('confirm', ['request' => $request]);
    }

    // サンクスページ
    public function thanks()
    {
        return view('thanks');
    }

    // 管理画面（ログイン後のみ）
    public function admin()
    {
        return view('admin');
    }
}
