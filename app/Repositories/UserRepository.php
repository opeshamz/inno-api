<?php


namespace App\Repositories;

use App\Interfaces\PreferenceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{

    private $preference;
    public function __construct(PreferenceInterface $preference)
    {
        $this->preference = $preference;
    }
    public function createUser(array $data): array
    {
        // To ensure both the user and their associated preferences are created atomically,
        // a transaction is applied.
        $data['password'] = Hash::make($data['password']);
        $user = null;
        $preference = null;
        try {
            DB::beginTransaction();
            $user = User::create($data);
            $preferencePayload = [
                'user_id' => $user->id
            ];
            $preference = $this->preference->createPreference($preferencePayload);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user or preference: ' . $e->getMessage());
        }
        return [
            'user' => $user,
            'preference' => $preference,
        ];
    }

    final public function loginUser(array $userCredentials)
     {
        $user = User::where('email', $userCredentials["email"])->first();
        if (!$user || !\Hash::check($userCredentials["password"], $user->password)) {
            return false;
        }
        $token = JWTAuth::fromUser($user);
        return [
             'token' => $token,
             'user' => $user,
        ];
     }
     public function logoutUser(): void
     {
        JWTAuth::invalidate(JWTAuth::getToken());
     }

}
