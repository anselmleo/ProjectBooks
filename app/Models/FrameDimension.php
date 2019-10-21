<?php


namespace App\Models;


class FrameDimension extends BaseModel
{
  protected $fillable = [
    'frame_type_id', 'name', 'price'
  ];

  protected $hidden = [
    'updated_at'
  ];

  public function getPriceAttribute($price)
  {
    return $price/100;
  }
}
