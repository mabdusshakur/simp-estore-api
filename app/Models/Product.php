<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'slug',
        'description',
        'regular_price',
        'sale_price',
        'category_id',
        'subcategory_id',
        'status',
        'stock',
        'view_count',
        'sold_count',
    ];
}
