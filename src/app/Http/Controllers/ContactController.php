<?php

namespace App\Http\Controllers; //このファイルが属する 名前空間（namespace） を指定

use App\Http\Requests\ContactRequest;
//フォームリクエスト用クラス   バリデーション済みのデータを取得できる
use App\Models\Category;
//カテゴリ情報のモデル  DB の categories テーブルと対応
use App\Models\Contact;
//お問い合わせ情報のモデル  DB の contacts テーブルと対応
use Illuminate\Http\Request;
//HTTP リクエストを表すクラス  フォームから送られたデータを取得できる
use Illuminate\Support\Facades\Date;
//日付操作用のヘルパークラス  タイムゾーンの変換やフォーマットに使える
use Symfony\Component\HttpFoundation\StreamedResponse;
//ストリームレスポンス用のクラス  CSV のような大きなファイルを一気に出力せずに逐次送信できる

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('contact', compact('categories')); // contact.blade.php を返す
    }
     // 確認画面
    public function confirm(Request $request)
    {
        $contacts = $request->all();  //フォームから送信された 全ての入力値を配列として取得
        $category = Category::find($request->category_id);
        //フォームで選択されたカテゴリID (category_id) をもとに、カテゴリ情報を取得
        return view('confirm', compact('contacts', 'category'));
        //compact() を使って変数をまとめてビューに渡す
        //$contacts → 入力内容
        //$category → カテゴリ情報
    }

    // サンクスページ
    public function store(ContactRequest $request)
    {
        if ($request->has('back')) {
            return redirect('/')->withInput();
        }
        //フォームの確認画面で「戻る」ボタンを押した場合
        //redirect('/') → 入力ページに戻る
        //withInput() → 入力した値を保持して戻す（再入力不要にする）

        $request['tell'] = $request->tel_1 . $request->tel_2 . $request->tel_3;  
        //フォームで電話番号を 3 つの入力欄に分けている場合に、
        //それを1つに結合して tell というフィールドに格納
        Contact::create(
            $request->only([
                'category_id',
                'first_name',
                'last_name',
                'gender',
                'email',
                'tell',
                'address',
                'building',
                'detail'
            ])
        );
        //$request->only([...]) → 指定した入力項目だけ取り出す
        //Contact::create([...]) → 取り出したデータを contacts テーブルに登録

        return view('thanks');
    }

    // 管理画面（ログイン後のみ）
    public function admin()
    {
        $contacts = Contact::with('category')->paginate(7);
        //Contact::with('category')→ Eloquent の リレーション を使って、Contact に紐づく Category の情報も一緒に取得
        //paginate(7)→ 1ページに7件ずつ表示するページネーションを作る
        $categories = Category::all(); //プルダウンや検索条件用に 全カテゴリを取得
        $csvData = Contact::all();  //CSV出力用に 全データを取得
        return view('admin', compact('contacts', 'categories', 'csvData'));  //compact() を使って変数をまとめてビューに渡す
        //$contacts → ページネーション用のデータ
        //$categories → カテゴリ選択用
        //$csvData → CSV出力用
    }

    //データの取得・絞り込み・ページネーション
    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/admin')->withInput();
        }
        // 管理画面で「検索条件をリセット」した場合
        // /admin にリダイレクトして、フォームを空に戻す
        // withInput() は入力値を一時的にセッションに保持して戻すためのもの
        $query = Contact::query();  //Eloquent のクエリビルダーを作成

        $query = $this->getSearchQuery($request, $query);  //検索条件をクエリに反映

        $contacts = $query->paginate(7); //ページネーションで取得（1ページ7件ずつ表示）
        $csvData = $query->get(); //同じ検索結果を CSV 出力用に取得
        $categories = Category::all(); //カテゴリ情報を取得してビューに渡す
        return view('admin', compact('contacts', 'categories', 'csvData')); //変数をまとめてビューに渡す
    }

    //削除処理
    public function destroy(Request $request)
    {
        //指定されたIDのレコードを取得   DBから削除
        Contact::find($request->id)->delete();
        return redirect('/admin'); //削除後に /admin にリダイレクトして管理画面を再表示
    }

    //管理画面の検索結果を CSV ファイルとしてダウンロードさせる処理
    public function export(Request $request)
    {
        $query = Contact::query(); //Eloquent クエリビルダーを作成

        $query = $this->getSearchQuery($request, $query);  //検索条件を追加する

        $csvData = $query->get()->toArray();  //絞り込んだ検索結果を取得

        $csvHeader = [
            'id', 'category_id', 'first_name', 'last_name', 'gender', 'email', 'tell', 'address', 'building', 'detail', 'created_at', 'updated_at'
        ];

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $createCsvFile = fopen('php://output', 'w'); // 標準出力を開く

            mb_convert_variables('SJIS-win', 'UTF-8', $csvHeader); // 文字コード変換（CSVはSJISで出力）

            fputcsv($createCsvFile, $csvHeader); // ヘッダー行を書き込む

            foreach ($csvData as $csv) {
                $csv['created_at'] = Date::make($csv['created_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');
                $csv['updated_at'] = Date::make($csv['updated_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');
                fputcsv($createCsvFile, $csv);
            }
            //取得したデータを1行ずつ CSV に書き込む
            //日付を日本時間（Asia/Tokyo）に変換し、Y/m/d H:i:s 形式に整形

            fclose($createCsvFile); //CSV書き込みが終わったらファイルを閉じる
        }, 200, //HTTPステータス OK
            [
            'Content-Type' => 'text/csv', //CSVファイルとしてブラウザに認識させる
            //「ダウンロードするファイル」として扱う
            'Content-Disposition' => 'attachment; filename="contacts.csv"', //ダウンロード時のファイル名
        ]);

        return $response;
    }

    //管理画面での 検索条件を DB クエリに反映する処理
    private function getSearchQuery($request, $query)
    {
        if(!empty($request->keyword)) //空でなければ処理
            {
            $query->where(function ($q) use ($request) //括弧付きの OR 条件 を作成
                {
                $q->where('first_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%');
                    //like '%keyword%' で 部分一致検索
                    //first_name OR last_name OR email にキーワードが含まれているレコードを取得
            });
        }

        if (!empty($request->gender)) {
            $query->where('gender', '=', $request->gender);
        }  //性別が指定されていれば、その値と一致するレコードだけ抽出

        if (!empty($request->category_id)) {
            $query->where('category_id', '=', $request->category_id);
        }  //選択したカテゴリーに一致するレコードだけ抽出

        if (!empty($request->date)) {
            $query->whereDate('created_at', '=', $request->date);
        }  //created_at の日付部分が $request->date と一致するレコードを抽出
           //時刻部分は無視して日付だけで比較

        return $query;
         //絞り込み条件を反映した Eloquent クエリビルダー を返す
         // これを search() や export() で使ってページネーションや CSV 出力に活かす
    }
}
