<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LundaryOrderItem extends Model
{
    use HasFactory;
    protected $table = 'lundary_order_item';
    protected $fillable = [
        'order_item_id',
        'ProductName',
        'ProductQty',
        'ProductCategroyId',
    ];


    public function categories()
    {
        return $this->belongsTo(Category::class, 'ProductCategroyId');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class, 'ProductName');
    }
}
