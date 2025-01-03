<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePreferenceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\PreferenceInterface;

class UserPreferenceController extends Controller
{
    //
    private $preference;
    public function __construct(PreferenceInterface $preference)
    {
        $this->preference = $preference;
    }
    final public function getUserPreference(Request $request): JsonResponse{
        $user = $request->user();
        try{
        $preference = $this->preference->getUserPreference($user->id);
        return response()->json([
            "status" => 'success',
            "message" => "User preference fetched successfully",
            'data' => $preference
        ], 200);
     } catch (\Exception $e){
            Log::error('User preference: '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while fetching.",
                'data' => []
            ], 500);
        }
    }
    final public function updatePreference (
        UpdatePreferenceRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        try{
        $preference = $this->preference->updateUserPreference($user->id,$validated);
        return response()->json([
                "status" => 'success',
                "message" => "User preference updated successfully",
                'data' => $preference
             ], 200);

      } catch (\Exception $e){
           Log::error('Error updating User preference: '.$e->getMessage());
           return response()->json([
           "status" => 'error',
           "message" => "An error occurred while updating.",
           'data' => []
          ], 500);
}

    }
}
