<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoritesController extends Controller
{
    /**
     * Toggle a pet in/out of the user's favorites.
     */
    public function toggle(Request $request, Pet $pet): JsonResponse
    {
        $user = $request->user();

        $exists = $user->favorites()->where('pet_id', $pet->id)->exists();

        if ($exists) {
            $user->favorites()->where('pet_id', $pet->id)->delete();
            $favorite = false;
        } else {
            $user->favorites()->create(['pet_id' => $pet->id]);
            $favorite = true;
        }

        return response()->json([
            'favorite' => $favorite,
            'pet_id' => $pet->id,
        ]);
    }

    /**
     * List the user's favorite pets.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $pets = $request->user()->favoritePets()->latest('favorites.created_at')->get();

        return PetResource::collection($pets);
    }
}
