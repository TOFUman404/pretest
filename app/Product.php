<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'stock',
        'available',
        'image_path',
        'created_by',
        'updated_by',
    ];
}
