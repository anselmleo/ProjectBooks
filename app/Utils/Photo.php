<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

trait Photo
{

  /**
   * Upload the request file 
   * 
   * @param $directoryName
   * @param $file
   * @param $name with extention
   * @param $disk
   * 
   * @return file path
   */
  public function upload($directoryName, $photo, $disk = 'local')
  {
    $path = '';

    if ($photo) {
      $name = $this->setPhotoName($photo);
      $path = Storage::disk($disk)->putFileAs('public/' . $directoryName, $photo, $name);
    }

    // if ($disk != 'local') {
    //   return "https://flexi-creative-bucket.s3-eu-west-2.amazonaws.com/$path";
    // }
    return $path;
  }

  /**
   * Attach timestamp to the original file name
   * 
   * @param $file
   * 
   * @return String $fileName
   */
  public function setPhotoName($photo)
  {
    $photoName = time() . '_' . $photo->getClientOriginalName();

    return $photoName;
  }

  /**
   * Download the requested file
   * 
   * @param String $filePath
   * @param String $name
   * 
   * @return File
   */
  public function download(String $filePath, String $name = null)
  {
    // add an if condition to set the name 
    $download = Storage::download($filePath);

    return $download;
  }

  /**
   * Get the request file size
   * 
   * @param String $file
   * 
   * @return $filePath
   */
  public function getFileSize($filePath)
  {
    return Storage::size($filePath);
  }


  // create a function that removes whitespaces from file
  public function FunctionName(Type $var = null)
  {
    # code...
  }
}
