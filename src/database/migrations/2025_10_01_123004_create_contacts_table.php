<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            //id カラム  contacts テーブルのレコードを一意に識別する番号
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            //category_id という 外部キー カラムを作ります。
            //cascadeOnDelete() は 参照先のカテゴリが削除されたら、この contacts レコードも自動削除 される
            $table->string('first_name');
            $table->string('last_name');
            //first_name と last_name は文字列カラム
            //ユーザーの名前を保存するために使う
            $table->tinyInteger('gender');
            //gender は小さな整数を保存するカラム
            //性別を数字で管理する場合に便利です（例：1=男性、2=女性、3=その他）
            $table->string('email');
            //ユーザーのメールアドレスを文字列で保存するカラム
            $table->string('tell');
            //tell は電話番号を文字列で保存するカラム
            $table->string('address');
            //住所の文字列を保存するカラム
            $table->string('building');
            //建物名や部屋番号などの補足住所の文字列を保存するカラム
            //入力が必須でない場合は ->nullable() を付けると空でも保存できる
            $table->text('detail');
            //お問い合わせ内容や詳細を保存するカラム
            //text 型なので 長い文章 も保存可能
            $table->timestamps();
            //created_at と updated_at カラムを自動で作成
            //レコードが いつ作成・更新されたか を自動で管理してくれます
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
