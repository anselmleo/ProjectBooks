<?php


namespace App\Http\Controllers;


use App\Repositories\Contracts\IPhotoRepository;
use App\Utils\Rules;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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
   *     path="/photos",
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
      $photo = $this->photoRepository->myJobs(auth()->id(), $perPage, $orderBy, $sort);
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

  /**
   * @OA\Get(
   *     path="/top-jobs",
   *     operationId="topJobs",
   *     tags={"Job Board Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Get top jobs",
   *     description="",
   *     @OA\Parameter(
   *         name="per_page",
   *         in="query",
   *         description="Number per page",
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
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
  public function topJobs()
  {
    $payload = request()->all();
    $perPage = request()->has('per_page') ? $payload['per_page'] : 5;

    try {
      $data = $this->jobRepository->getTopJobs($perPage);
      return $this->withData($data);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * @OA\Get(
   *     path="/my-area-jobs",
   *     operationId="myAreaJobs",
   *     tags={"Job Board Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Get jobs in my area",
   *     description="",
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
  public function myAreaJobs()
  {
    $payload = request()->all();
    $perPage = request()->has('per_page') ? $payload['per_page'] : 5;
    $orderBy = request()->has('order_by') ? $payload['order_by'] : 'created_at';
    $sort = request()->has('sort') ? $payload['sort'] : 'desc';

    try {
      $data = $this->jobRepository->getJobsInMyArea(auth()->id(), $perPage, $orderBy, $sort);
      return $this->withData($data);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * @OA\Get(
   *     path="/bid-status/{job_id}",
   *     operationId="bidStatus",
   *     tags={"Job Board Operations"},
   *     security={{"authorization_token": {}}},
   *     summary="Check if a worker has applied for a job",
   *     description="",
   *      @OA\Parameter(
   *         name="job_id",
   *         in="path",
   *         description="The Job id",
   *         required=true,
   *         @OA\Schema(
   *             type="integer",
   *             format="int64"
   *         )
   *     ),
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   * @param $job_id
   * @return JsonResponse
   */
  public function bidStatus($job_id)
  {
    try {
      $data = $this->jobRepository->hasApplied(auth()->id(), $job_id);
      return $this->withData($data);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }
}
