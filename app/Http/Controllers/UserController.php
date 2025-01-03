<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UserRequest;
use App\Interfaces\PreferenceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $userRepository;
    public function __construct(
        UserRepositoryInterface $userRepository

    )
    {
        $this->userRepository = $userRepository;
    }
    final public function createUser(UserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $result = $this->userRepository->createUser($validated);
            return response()->json([
                "status"=> 'success',
                "message"=> "User created successfully",
                "data"=>$result
            ], 201);
        }
        catch (\Exception $e){
            Log::error('Error creating user: '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while creating the user.",
                'data' => []
            ], 500);
        }
        }
    final public function login(LoginUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
       try{
        $result = $this->userRepository->loginUser($validated);
        if(!$result){
            return response()->json([
                "status" => 'failed',
                "message" => "Invalid Credentials",
                "data" => []
            ], 401);
        }
        return response()->json([
            "status" => 'success',
            "message" => "User lodged successfully",
            "data" => $result
        ], 200);

      } catch (\Exception $e){
        Log::error('User login error: '.$e->getMessage());
        return response()->json([
            "status" => 'error',
            "message" => "An error occurred while login.",
            'data' => []
        ], 500);
    }
    }
    public function logout(): JsonResponse
    {
        try {
            $this->userRepository->logoutUser();
            return response()->json([
                "status" => 'success',
                "message" => "Successfully logged out",
                "data" => []
            ], 200);
        }
        catch (\Exception $e){
            Log::error('User login error: '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while logout.",
                'data' => []
            ], 500);
        }
    }
    public function getUser(Request $request)
    {
        return response()->json([
            'status' => 'success',
            "message" => "User details fetched successfully",
            'data' =>  $request->user(),
        ]);
    }
}
