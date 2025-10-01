<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

//HasFactory → テスト・シーディングで便利
//$guarded → mass assignment の安全管理
//リレーション → belongsTo を使って Category と紐付け
