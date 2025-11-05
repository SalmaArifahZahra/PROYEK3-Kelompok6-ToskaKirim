<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_categories';

    protected $fillable = [
        'categories_name',
    ];

    public function products()
    {
        return $this->hasMany(ProductModel::class, 'id_categories');
    }
}
