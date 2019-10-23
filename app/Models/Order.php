<?php


namespace App\Models;


class Order extends BaseModel
{
  protected $fillable = [
    'full_name', 'frame_type', 'frame_image', 'frame_text', 'frame_dimension', 
    'shipping_addr', 'state', 'extra_note'
  ];

  protected $hidden = [
    'updated_at'
  ];  
}
