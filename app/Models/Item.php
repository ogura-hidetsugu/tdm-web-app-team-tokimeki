<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public const CATEGORIES = [
        '食品',
        '飲み物',
        '日用品',
        '消耗品',
        'コスメ',
        '薬サプリ',
        'その他',
    ];

    public const PURCHASE_LOCATIONS = [
        'スーパー',
        '薬局',
        'Amazon',
        '楽天',
        'その他',
    ];

    public const PURCHASE_CYCLES = [
        '毎週',
        '隔週',
        '毎月',
        '必要なとき',
    ];

    protected $fillable = [
        'name',
        'category',
        'purchase_location',
        'purchase_cycle',
        'memo',
        'quantity',
    ];
}