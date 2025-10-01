<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategoriesTableSeeder::class,
            //CategoriesTableSeeder::class → カテゴリー用の初期データを作るシーダー
            ContactsTableSeeder::class
            //ContactsTableSeeder::class → お問い合わせデータを作るシーダー
        ]);
    }
}
