<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProductCategory extends Model
{
    use HasFactory, Sluggable, SearchableTrait;
    protected $table = 'product_categories';


    protected $fillable = ['name', 'slug', 'cover', 'status', 'parent_id',];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function status(){
        return $this->status ? 'Active':'Inactive';
    }
    protected $searchable = [
      
        'columns' => [
            'product_categories.name' => 10,
        ]
    ];

    public function parent()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    }

    public function appearedChildren()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id')->where('status', true);
    }

    public static function tree($level = 1)
    {
        return static::with(implode('.', array_fill(0, $level, 'children')))
            ->whereNull('parent_id')
            ->whereStatus(true)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }
}
