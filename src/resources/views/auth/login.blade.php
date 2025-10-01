@extends('layouts.app')
{{-- layouts/app.blade.php という共通レイアウトを使う
//ヘッダーやフッターなど共通部分をまとめて管理できる--}}

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection
{{-- login.css を読み込む
//asset() は Laravel のヘルパーで、public フォルダ内のファイルパスを生成 --}}

@section('link')
<a class="header__link" href="/register">register</a>
@endsection
{{-- ログイン画面のヘッダーなどに「新規登録リンク」を設置 --}}


@section('content')
{{-- @section('content') で layouts.app の @yield('content') に埋め込まれる
//ここにログインフォームの HTML が入る--}}
<div class="login-form">
  <h2 class="login-form__heading content__heading">Login</h2>
  <div class="login-form__inner">
    <form class="login-form__form" action="/login" method="post">
        {{-- action="/login" → フォーム送信先 method="post" → POST で送信 --}}
      @csrf
        {{-- @csrf → CSRF 保護（Laravel必須 --}}
      <div class="login-form__group">
        <label class="login-form__label" for="email">メールアドレス</label>
        <input class="login-form__input" type="mail" name="email" id="email" placeholder="例: test@example.com">
        <p class="register-form__error-message">
            {{-- @error('email') → バリデーションエラーがある場合にメッセージ表示 --}}
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="login-form__group">
        <label class="login-form__label" for="password">パスワード</label>
        <input class="login-form__input" type="password" name="password" id="password" placeholder="例: coachtech1106">
        <p>
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="login-form__btn btn" type="submit" value="ログイン">
    </form>
  </div>
</div>
@endsection('content')