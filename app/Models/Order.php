<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'total',
        'status',
        'payment_method',
        'transaction_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
