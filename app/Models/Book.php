<?php


namespace App\Models;


class Book extends BaseModel
{
    protected $fillable = [
        'title', 'description', 'cover_image', 'image_path', 'category_id', 'author_name'
    ];

    protected $hidden = [
        'updated_at'
    ];
  
    public function Category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')
            ->select(['id', 'name',]);
    }

}
