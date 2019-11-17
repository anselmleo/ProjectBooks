<?php


namespace App\Models;


class Book extends BaseModel
{
    protected $fillable = [
        'name', 'category', 'author'
    ];

    protected $hidden = [
        'updated_at'
    ];

    // public function Category()
    // {
    //     return $this->hasOne(Category::class, 'id', 'category_id')
    //         ->select(['id', 'name',]);
    //   
    // }

    // public function Author()
    // {
    //     return $this->hasOne(Author::class, 'id', 'author_id')
    //         ->select(['id', 'email', 'phone']);
    // }
}
