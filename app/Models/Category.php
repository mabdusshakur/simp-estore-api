<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['name','slug','products_count','sub_categories_count'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
