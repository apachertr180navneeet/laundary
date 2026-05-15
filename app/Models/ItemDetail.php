<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    use HasFactory;

    protected $table = 'item_detail';
    protected $fillable = [
        'item_id',
        'category',
        'service',
        'price'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
