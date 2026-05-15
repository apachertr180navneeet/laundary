<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'company_id',
        'order_id',
        'invoice_number',
        'id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
