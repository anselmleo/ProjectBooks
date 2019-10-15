<?php


namespace App\Models;


class Photo extends BaseModel
{
  protected $fillable = [
    'user_id', 'title', 'path', 'b64-string'
  ];

  protected $hidden = [
    'updated_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class)->with('profile');
  }

  public function city()
  {
    return $this->hasOne(City::class, 'id', 'city_id')->select([
      'id', 'name'
    ]);
  }

  public function category()
  {
    return $this->hasOne(Category::class, 'id', 'category_id')->select([
      'id', 'name'
    ]);
  }
}
