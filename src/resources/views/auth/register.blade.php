@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css')}}">
@endsection

@section('link')
<a class="header__link" href="/login">login</a>
@endsection

@section('content')
<div class="register-form">
    {{-- 入力欄1つ分をまとめる グループ --}}
  <h2 class="register-form__heading content__heading">Register</h2>
  <div class="register-form__inner">
    <form class="register-form__form" action="/register" method="post">
      @csrf
      <div class="register-form__group">
        <label class="register-form__label" for="name">お名前</label>
        {{-- 入力欄の ラベル（「お名前」と表示される文字）
            for="name" は、このラベルが <input id="name"> と関連していることを示す --}}
        <input class="register-form__input" type="text" name="name" id="name" placeholder="例：山田 太郎">
        {{-- 実際の入力欄 を作るタグ
            class="register-form__input" → CSSでスタイルを適用
            type="text" → 文字列入力用
            name="name" → フォーム送信時のキー名。サーバーで $request->name で受け取れる id="name" → <label for="name"> と対応させるため
            placeholder="例：山田 太郎" → 入力欄内に薄いグレーで表示されるヒント--}}
        <p class="register-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
        {{-- バリデーションエラー用の表示
             Laravel の Blade ディレクティブ @error('name')
             ：サーバーで name フィールドのバリデーションに失敗した場合に中のコードを表示
             {{ $message }} はエラーメッセージ 
             <p> で囲むことで CSS で色や余白を付けられる--}}
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="email">メールアドレス</label>
        <input class="register-form__input" type="mail" name="email" id="email" placeholder="例：test@example.com">
        <p class="register-form__error-message">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="password">パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password" placeholder="例：coachtech1106">
        <p class="register-form__error-message">
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="register-form__btn btn" type="submit" value="登録">
    </form>
  </div>
</div>
@endsection('content')