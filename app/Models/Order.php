<?php


namespace App\Models;


class Order extends BaseModel
{
  protected $fillable = [
    'full_name', 'email', 'phone', 'frame_type', 'frame_image', 'frame_image_path', 'frame_text', 'frame_dimension', 
    'shipping_addr', 'state', 'extra_note', 'is_received', 'is_processing', 
    'is_shipped', 'is_delivered', 'is_completed'
  ];

  protected $hidden = [
    'updated_at'
  ];

  // public function getFrameTypeAttribute($frameType)
  // {
  //   switch ($frameType) {
  //     case 1:
  //       # return frame type name
  //       return $frameType = "Regular";
  //       break;

  //     case 2:
  //       # return frame type name
  //       return $frameType = "Illustration";
  //       break;

  //     case 3:
  //       # return frame type name
  //       return $frameType = "Quote";
  //       break;
      
  //     default:
  //       # code...
  //       break;
  //   }
  // }

  // public function getFrameDimensionAttribute($frameDimension)
  // {
  //   switch ($frameDimension) {
  //     case 1:
  //       # return frame type name
  //       return $frameDimension = "Regular";
  //       break;

  //     case 2:
  //       # return frame type name
  //       return $frameDimension = "Illustration";
  //       break;

  //     case 3:
  //       # return frame type name
  //       return $frameDimension = "Quote";
  //       break;

  //     default:
  //       # code...
  //       break;
  //   }
  // }
}
