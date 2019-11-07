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

  public function getIsPaidAttribute($isPaid)
  {
    if($isPaid) {
      return true;
    } else {
      return false;
    }
  }

  public function getIsReceivedAttribute($isReceived)
  {
    if ($isReceived) {
      return true;
    } else {
      return false;
    }
  }

  public function getIsProcessingAttribute($isProcessing)
  {
    if ($isProcessing) {
      return true;
    } else {
      return false;
    }
  }

  public function getIsShippedAttribute($isShipped)
  {
    if ($isShipped) {
      return true;
    } else {
      return false;
    }
  }

  public function getIsDeliveredAttribute($isDelivered)
  {
    if ($isDelivered) {
      return true;
    } else {
      return false;
    }
  }

  public function getIsCompletedAttribute($isCompleted)
  {
    if ($isCompleted) {
      return true;
    } else {
      return false;
    }
  }

  public function frameType()
  {
      return $this->belongsTo('App\Models\FrameType', 'frame_type');
  }

  public function frameDimension()
  {
    return $this->belongsTo('App\Models\FrameDimension', 'frame_dimension');
  }
}