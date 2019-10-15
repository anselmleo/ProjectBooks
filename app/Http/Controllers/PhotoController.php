<?php


namespace App\Http\Controllers;


use App\Repositories\Contracts\IPhotoRepository;
use App\Utils\Rules;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Photo;


class PhotoController extends Controller
{
  /**
   * @var IPhotoRepository
   */
  private $photoRepository;

  public function __construct(IPhotoRepository $photoRepository)
  {
    $this->middleware('auth:api', ['except' => 'freePhotos']);

    $this->photoRepository = $photoRepository;
  }

  /**
   * @OA\Get(
   *     path="/photos/my-photos",
   *     operationId="myPhotos",
   *     tags={"Photo Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Get my photos",
   *     description="Can be performed by authenticated user",
   *     @OA\Parameter(
   *         name="per_page",
   *         in="query",
   *         description="Number per page",
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
   *         )
   *     ),
   *     @OA\Parameter(
   *         name="order_by",
   *         in="query",
   *         description="Order by a column",
   *         @OA\Schema(
   *             type="string",
   *         )
   *     ),
   *     @OA\Parameter(
   *         name="sort",
   *         in="query",
   *         description="desc or asc",
   *         @OA\Schema(
   *             type="string",
   *         )
   *     ),
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   * @return JsonResponse
   */
  public function myJobs()
  {
    $payload = request()->all();
    $perPage = request()->has('per_page') ? $payload['per_page'] : 15;
    $orderBy = request()->has('order_by') ? $payload['order_by'] : 'created_at';
    $sort = request()->has('sort') ? $payload['sort'] : 'desc';

    try {
      $photo = $this->photoRepository->myPhotos(auth()->id(), $perPage, $orderBy, $sort);
      return $this->withData($photo);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }


  /**
   * @OA\Get(
   *     path="/photos/{photo_id}",
   *     operationId="getSinglePhoto",
   *     tags={"Common"},
   *     security={{"authorization_token": {}}},
   *     summary="Get single photo",
   *     description="",
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   * @return JsonResponse
   */
  public function getSinglePhoto($photo_id) {
    try {
      $photo = $this->photoRepository->getSinglePhoto($photo_id);
      return $this->withData($photo);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * @OA\Get(
   *     path="/dashboard-stat",
   *     operationId="dashboardStat",
   *     tags={"Common"},
   *     security={{"authorization_token": {}}},
   *     summary="Get dashboard metrics",
   *     description="",
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   * @return JsonResponse
   */
  public function dashboardStat()
  {
    try {
      $data = $this->photoRepository->dashboardStat(auth()->id());
      return $this->withData($data);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }


  public function uploadPhoto(Request $request, $disk = 'local') {

    // $validator = Validator::make(request()->all(), Rules::get('CREATE_JOB'));
    // if ($validator->fails()) {
    //   return $this->validationErrors($validator->getMessageBag()->all());
    // }

    // try {
    //   $this->jobRepository->createJob(auth()->id(), request()->all());
    //   return $this->success("Job created successfully");
    // } catch (Exception $e) {
    //   return $this->error($e->getMessage());
    // }

    try {
      if ($request->hasFile('photo_image')) {

        //Get full filename
        $filenameWithExt = $request->file('photo_image')->getClientOriginalName();

        //Extract filename only
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //Extract extenstion only
        $extension = $request->file('photo_image')->getClientOriginalExtension();

        //Combine again with timestamp in the middle to differentiate files with same filename.
        $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

        $path = $request->file('photo_image')->storeAs('public/photo_images', $filenameToStore);
        // $path = Storage::disk($disk)->putFileAs('public/photo_images', $filenameToStore, 'public');
        // dd($path);
        $user_id = auth()->id();

        $photo = Photo::create([
          'user_id' => $user_id,
          'name' => $filenameToStore,
          'path' => $path,
        ]);

        return response()->json([
          'status' => true,
          'message' => 'photo uploaded successfully',
          'data' => $photo
        ]);
      }
      dd('i now got here');
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }

    dd('after catch');
    

    
  }

  public function uploadPhoto64() 
  {
    $user_id = auth()->id();
    $title = str_random(5) . '_' . time();
    $b64String = request()->get('b64-string');
    
    $photo = Photo::create([
      'user_id' => $user_id,
      'title' => $title,
      'b64-string' => $b64String
    ]);

    return response()->json([
      'status' => true,
      'message' => 'photo stored successfully',
      'data' => $photo
    ]);
  }
  
}
