<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            "商品のお届けについて",
            "商品の交換について",
            "商品トラブル",
            "ショップへのお問い合わせ",
            "その他"
        ];
        //$contents という変数に、カテゴリー名の文字列を5つ入れた配列

        foreach ($contents as $content) {
            //$contents の中身を 1つずつ $content という変数に代入して処理
            DB::table('categories')->insert([
                'content' => $content,
            ]);
        }
    }
}
//DB::table('categories')→ categories テーブルを操作する準備をする。
//insert([...])→ 配列の形でデータを入れると、そのまま DB に新しいレコードとして追加されます。
//'content' => $content→ テーブルの content カラムに $content の文字列を入れる。