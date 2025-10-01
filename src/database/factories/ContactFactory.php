<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;
    //Contact::factory() が正しくこのファクトリを使うことを Laravel が認識できる


    public function definition()
    {
        return [
            'category_id' => $this->faker->numberBetween(1, 5),
            //category_id に 1〜5 の数字 をランダムで入れる
            'first_name' => $this->faker->lastName(),
            //first_name カラムに ランダムな名字 を入れる
            'last_name' => $this->faker->firstName(),
            //last_name カラムに ランダムな名前 を入れる
            'gender' => $this->faker->randomElement([1, 2, 3]),
            //gender に 1, 2, 3 のいずれか をランダムで入れる
            'email' => $this->faker->safeEmail(),
            //ランダムな メールアドレス を生成
            'tell' => $this->faker->phoneNumber(),
            //ランダムな 電話番号 を生成
            'address' => $this->faker->city() . $this->faker->streetAddress(),
            //住所カラムに 市名＋番地 をランダムで生成
            'building' => $this->faker->secondaryAddress(),
            //建物名や部屋番号などをランダム生成
            'detail' => $this->faker->text(120),
            //お問い合わせ内容などの テキスト（最大120文字） をランダム生成
        ];
    }
}

//Contact::factory()->count(10)->create(); で リアルなダミーデータ 10 件 が作れる