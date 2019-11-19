<?php


namespace App\Repositories\Concretes;

use App\Jobs\SendChangePasswordEmail;
use App\Jobs\SendVerificationEmailJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Jobs\UpdateLastLoginJob;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersVerification;
use App\Repositories\Contracts\IUserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    private $user;

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user_id): void
    {
        $this->user = User::find($user_id);
    }

    /**
     * @param array $params
     * @throws Exception
     */
    public function register(array $params, $role): void
    {
        [
            'email' => $email,
            'phone' => $phone,
            'password' => $password
        ] = $params;

        try {
            // Persist data
            $user = User::create([
                'email' => $email,
                'phone' => $phone,
                'password' => bcrypt($password)
            ]);

            $isNull = is_null($user);
            if ($isNull) 
                throw new Exception ("Could not create a user. Null parameter received");

            $user_id = $user->id;
            $this->setUser($user_id);

            // $splitName = explode(' ', $full_name, 2); // Restricts it to only 2 values, for names like Billy Bob Jones

            // $first_name = $splitName[0];
            // $last_name = !empty($splitName[1]) ? $splitName[1] : ''; // If last name doesn't 

            $profile =$this->getUser()->profile()->create([
                'full_name' => $full_name,
                'avatar' => Profile::AVATAR,
            ]);

            $isNull = is_null($profile);
            if ($isNull)
                throw new Exception ("Could not create user profile");

            // Attach user role
            $this->assignRole($role);
            
            // Generate user verification token
            if (!$this->createVerificationToken())
                throw new Exception("Could not create verification token for the registered user with id ${user_id}");
            dd($user->verificationToken->token);
            if (!$this->activate())
                throw new Exception("Could not activate user user with id ${user_id}");
                
            // Push this verification email to the queue (Basically sends this email to the registered user)
            dispatch(new SendVerificationEmailJob($this->getUser()));
        } catch (Exception $e) {
            report($e);

            //Delete the user to avoid duplicate entry.
            $this->getUser()->delete();

            // Return a custom error message back....
            throw new Exception("Unable to create user, please try again");
        }
    }

    /**
     * @param $credentials
     * @return array
     * @throws Exception
     */
    public function authenticate(array $credentials): array
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new Exception("Incorrect email/phone or password");
        }

        $this->setUser(auth()->id());

        if ($this->isNotConfirmed()) {
            throw new Exception("E-mail not verified! Kindly check your e-mail to confirm");
        }

        if ($this->isBan()) {
            throw new Exception("User is banned! Kindly contact the admin");
        }

        // Update last login
        dispatch((new UpdateLastLoginJob(auth()->id(), request()->ip()))
            ->delay(Carbon::now()->addSeconds(10)));

        $profile = ($this->getUser()->hasRole(Role::USER)) ? $this->getUserDetails()
            : $this->getFullDetails();

        return [
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL()/60 . ' hours',
            'payload' => $profile
        ];
    }

    public function activate(): bool
    {
        return $this->getUser()->update([
            'is_active' => true
        ]);
    }

    public function createVerificationToken(): UsersVerification
    {
        return $this->getUser()->verificationToken()->create([
            'token' => str_random(40)
        ]);
    }

    /**
     * @param $role
     * @throws Exception
     */
    public function assignRole($role): void
    {
        $user_role = Role::where('name', $role)->first();
        if (!$user_role) {
            throw new Exception("Unable to find expected role in the system");
        }

        $this->getUser()->attachRole($user_role);
    }

    public function setUserWithToken($token): void
    {
        $valid_token = UsersVerification::where('token', $token)->first();
        if (!$valid_token) {
            throw new ModelNotFoundException("Invalid token");
        }
        $this->setUser($valid_token->user->id);
    }

    public function isConfirmed(): bool
    {
        return $this->getUser()->is_confirmed ?? false;
    }

    public function isNotConfirmed(): bool
    {
        return !$this->getUser()->is_confirmed ?? true;
    }

    public function isBan(): bool
    {
        return $this->getUser()->is_ban ?? true;
    }

    public function getFullDetails(): User
    {
        return User::with(['profile', 'roles', 'lastLogin'])->find($this->user->id);
    }

    public function getUserDetails()
    {
        return User::with(['profile', 'roles', 'lastLogin'])->find($this->getUser()->id);
    }

    /**
     * @return mixed
     */
    public function confirmUser(): bool
    {
        return $this->getUser()->update([
            'is_confirmed' => true
        ]);
    }

    /**
     * @param $token
     * @throws Exception
     */
    public function verifyEmail($token): void
    {
        try {
            $this->setUserWithToken($token);

            if ($this->isConfirmed()) {
                throw new Exception('User\'s e-mail is already verified! Kindly proceed to login');
            }

            if (!$this->confirmUser()) {
                throw new Exception("Could not confirm user with user_id " . $this->user->id);
            }

            dispatch(new SendWelcomeEmailJob($this->getUser()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateLastLogin($user_id, $ip): void
    {
        $this->setUser($user_id);
        $this->getUser()->lastLogin()->updateOrCreate(
            ['user_id' => $this->user->id],
            [
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $ip
            ]
        );
    }

    public function profile(int $user_id, array $params): User
    {
        $this->setUser($user_id);

        $this->getUser()->profile()->update([
            'full_name' => $params['full_name'],
            'gender' => $params['gender'],
            'avatar' => $params['avatar'],
            'address' => $params['address'],
            'city' => $params['city'],
            'state' => $params['state'],
        ]);

        $this->updateProfileStatus();

        if ($this->getUser()->hasRole(Role::USER)) {
            return $this->getUserDetails();
        }

        return $this->getFullDetails();
    }
    
    

    public function updateProfileStatus(): void
    {
        $this->getUser()->update([
            'profile_updated' => true
        ]);
    }

    
    public function isProfileUpdated(): bool
    {
        return $this->getUser()->profile_updated ?? false;
    }

    /**
     * @param int $user_id
     * @param array $params
     * @throws Exception
     */
    public function updatePassword(int $user_id, array $params): void
    {
        $this->setUser($user_id);

        [
            'current_password' => $current_password,
            'new_password' => $new_password,
        ] = $params;

        if (!Hash::check($current_password, $this->getUser()->password))
            throw new Exception("Current password is incorrect");

        $this->getUser()->update([
            'password' => app('hash')->make($new_password)
        ]);

        dispatch(new SendChangePasswordEmail($this->getUser()));
    }

    public function getUsers($perPage = 15, $orderBy = 'created_at', $sort = 'desc')
    {
        return User::orderBy($orderBy, $sort)->paginate($perPage);
    }
}
