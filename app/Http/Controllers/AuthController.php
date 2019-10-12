<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use App\Utils\Rules;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\IWorkerRepository as IWorkerRepositoryAlias;
use App\Repositories\Contracts\IEmployerRepository;
use App\Repositories\Contracts\IUserRepository;
use function GuzzleHttp\Promise\all;

class AuthController extends Controller
{
    /**
     * @var IUserRepository
     */
    private $userRepo;

    /**
     * Create a new controller instance.
     *
     * @param IUserRepository $userRepo
     */
    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @OA\Post(
     *     path="/user-registration",
     *     operationId="userRegistration",
     *     tags={"Authentication"},
     *     summary="Register a new user",
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
     *                  description="",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="last_name",
     *                  description="",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  description="",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  description="",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  description="",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="password_confirmation",
     *                  description="",
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
    public function registerUser()
    {
        return $this->register(Role::USER, ['email' => 'sometimes|unique:users|email']);
    }

    /**
     * @OA\Post(
     *     path="/confirm-email",
     *     operationId="verifyEmail",
     *     tags={"Authentication"},
     *     summary="Verify user's e-mail",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  description="Token",
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
    public function confirmEmail()
    {
        $payload = request()->only(['token']);

        $validator = Validator::make($payload, Rules::get('CONFIRM_EMAIL'));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        ['token' => $token] = $payload;

        try {
            $this->userRepo->verifyEmail($token);
            return $this->success("E-mail successfully verified! Kindly login to access every opportunity Timbala has to offer!");
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/authenticate",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="Authenticate existing user",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Request object",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="email",
     *                  description="Accepts email or phone",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  description="",
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
    public function authenticate()
    {
        $validator = Validator::make(request()->all(), Rules::get('AUTHENTICATE'));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        if (is_numeric(request()->email)) {
            request()['phone'] = request()->email;
            $credentials = request()->only(['phone', 'password']);
        } else {
            $credentials = request()->only(['email', 'password']);
        }

        try {
            $auth = $this->userRepo->authenticate($credentials);
            return $this->withData($auth);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    private function register($role, $extra_validation = [])
    {
        $validator = Validator::make(request()->all(), Rules::get('REGISTER_USER', $extra_validation));
        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }

        try {
            $this->userRepo->register(request()->all(), $role);
            return $this->success("Registration successful, check your e-mail and kindly click to verify!");
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}

