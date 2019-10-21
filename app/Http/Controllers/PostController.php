<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
  public function post(Request $request)
  {
    $this->validate($request, [
      'full_name' => 'required|string',
      'email' => 'required|email',
      'phone' => 'required|digits:11',
      'frame_type' => 'required|string',
      'frame_image' => 'required_without:frame_text|image|mimes:jpeg,png,jpg',
      'frame_text' => 'required_without:frame_image|string',
      'frame_dimension' => 'required|string',
      'shipping_addr' => 'required|string',
      'state' => 'required|string',
      'extra_note' => 'string'
    ]);

    $user = new User;
    $user->full_name = $request->get('full_name');
    $user->email = $request->get('email');
    $user->phone = $request->get('phone');
    $user->save();

    $post = new Post;
    $post->full_name = $request->get('full_name');
    $post->frame_type = $request->get('frame_type');

    if ($request->hasFile('frame_image')) {
      //Get full filename
      $filenameWithExt = $request->file('frame_image')->getClientOriginalName();

      //Extract filename only
      $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      //Extract extenstion only
      $extension = $request->file('frame_image')->getClientOriginalExtension();

      //Combine again with timestamp in the middle to differentiate files with same filename.
      $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;
      
      $request->file('frame_image')->storeAs('public/frame_images', $filenameToStore);

      $post->frame_image = $request->get('frame_image');
    } else {
      $post->frame_text = $request->get('frame_text');
    }


    $post->frame_dimension = $request->get('frame_dimension');
    $post->shipping_addr = $request->get('shipping_addr');
    $post->state = $request->get('state');
    $post->extra_note = $request->get('extra_note');
    $post->save();

    return response()->json([
      "payload" => "Post sent successfully!"
    ]);

  }
}
