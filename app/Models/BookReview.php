<?php


namespace App\Models;


class BookReview extends BaseModel
{
  protected $fillable = [
    'book_id', 'user_id', 'no_of_stars', 'remark'
  ];

  protected $hidden = [
    'updated_at'
  ];

  public function User()
  {
    return $this->hasOne(User::class, 'id', 'user_id')
      ->select(['id', 'email', 'phone'])
      ->with('profile');
  }
  
}
