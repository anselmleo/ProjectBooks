<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{

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
  public function dashboardElements()
  {
    try {
      $data = $this->photoRepository->dashboardStat(auth()->id());
      return $this->withData($data);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }



  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }


  
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $user_id = auth()->user()->id;
    $user = User::find($user_id);
    return view('dashboard')->with('posts', $user->post);
  }
}
