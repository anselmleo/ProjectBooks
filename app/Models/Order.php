<?php


namespace App\Models;


class Order extends BaseModel
{
  protected $fillable = [
    'full_name', 'frame_type', 'frame_image', 'frame_text', 'frame_dimension', 
    'shipping_addr', 'state', 'extra_note', 'is_received', 'is_processing', 
    'is_shipped', 'is_delivered', 'is_completed'
  ];

  protected $hidden = [
    'updated_at'
  ];  
}
