<?php


namespace App\Http\Controllers;


use App\Repositories\Contracts\IUserRepository;
use App\Utils\Rules;
use Exception;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->middleware('auth:api', ['except' => [
            'paymentCallback'
        ]]);

        $this->middleware('role:super_admin|admin', ['only' => [
            'allUsers'
        ]]);

        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Patch(
     *     path="/profile",
     *     operationId="profile",
     *     tags={"User Management"},
     *     security={{"authorization_token": {}}},
     *     summary="Update user's profile",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="first_name",
     *                  description="First Name",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="last_name",
     *                  description="Last Name",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="avatar",
     *                  description="Profile Picture, accepts base64 string",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="gender",
     *                  description="Gender",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="date_of_birth",
     *                  description="Date of birth",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="address",
     *                  description="Address",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="city",
     *                  description="City",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="state",
     *                  description="State",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="bio",
     *                  description="Brief description about yourself",
     *                  type="string",
     *              ),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns response object",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Error: Unproccessble Entity. When required parameters were not supplied correctly.",
     *          @OA\JsonContent()
     *     )
     * )
     */
    public function profile()
    {
        $payload = request()->all();

        $validator = Validator::make($payload, Rules::get('UPDATE_PROFILE'));

        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        try {
            $profile = $this->userRepository->profile(auth()->id(), $payload);
            return $this->withData($profile);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    
    

    
    /**
     * @OA\Patch(
     *     path="/update-password",
     *     operationId="changePassword",
     *     tags={"User Management"},
     *     security={{"authorization_token": {}}},
     *     summary="Change user's password",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="current_password",
     *                  description="Current user's password",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="new_password",
     *                  description="New password",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="new_password_confirmation",
     *                  description="Confirm New password",
     *                  type="string",
     *              )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns response object",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Error: Unproccessble Entity. When required parameters were not supplied correctly.",
     *          @OA\JsonContent()
     *     )
     * )
     */
    public function updatePassword()
    {
        $payload = request()->all();

        $validator = Validator::make($payload, Rules::get('CHANGE_PASSWORD'));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        try {
            $this->userRepository->updatePassword(auth()->id(), request()->all());
            return $this->success("Password successfully changed!");
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    

    /**
     * @OA\Post(
     *     path="/subscribe",
     *     operationId="subscribe",
     *     tags={"User Management"},
     *     security={{"authorization_token": {}}},
     *     summary="Subscribe to premium membership",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="callback_url",
     *                  description="The url to the page to return to after payment collection",
     *                  type="string",
     *              )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns response object",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Error: Unproccessble Entity. When required parameters were not supplied correctly.",
     *          @OA\JsonContent()
     *     )
     * )
     */
    public function subscribe()
    {
        $validator = Validator::make(request()->only('callback_url'), Rules::get('SUBSCRIBE'));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        try {
            $response = $this->userRepository->subscribe(auth()->id(), request()->callback_url);
            return $this->withData($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function verifyBvn($user_id)
    {
        try {
            $response = $this->userRepository->bvnVerification($user_id);
            return $this->withData($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/all-users",
     *     operationId="getAllUsers",
     *     tags={"Admin Operations"},
     *     security={{"authorization_token": {}}},
     *     summary="Get all users",
     *     description="Can only be performed by an admin",
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
     */
    public function allUsers()
    {
        $payload = request()->all();
        $perPage = request()->has('per_page') ? $payload['per_page'] : 15;
        $orderBy = request()->has('order_by') ? $payload['order_by'] : 'created_at';
        $sort = request()->has('sort') ? $payload['sort'] : 'desc';

        try {
            $response = $this->userRepository->allUsers($perPage, $orderBy, $sort);
            return $this->withData($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
