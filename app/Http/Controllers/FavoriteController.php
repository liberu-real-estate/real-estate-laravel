<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    /**
     * Get all favorites for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $favorites = $user->favoriteProperties()
            ->with(['images', 'neighborhood', 'features'])
            ->paginate(12);

        return response()->json($favorites);
    }

    /**
     * Add a property to the user's favorites.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|exists:properties,id',
            ]);

            $user = Auth::user();
            
            // Check if already favorited
            $exists = Favorite::where('user_id', $user->id)
                ->where('property_id', $validated['property_id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Property is already in your wishlist',
                ], 422);
            }

            $favorite = Favorite::create([
                'user_id' => $user->id,
                'property_id' => $validated['property_id'],
                'team_id' => $user->currentTeam?->id,
            ]);

            return response()->json([
                'message' => 'Property added to wishlist successfully',
                'favorite' => $favorite->load('property'),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add property to wishlist',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove a property from the user's favorites.
     */
    public function destroy(Request $request, $propertyId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $favorite = Favorite::where('user_id', $user->id)
                ->where('property_id', $propertyId)
                ->first();

            if (!$favorite) {
                return response()->json([
                    'message' => 'Property not found in your wishlist',
                ], 404);
            }

            $favorite->delete();

            return response()->json([
                'message' => 'Property removed from wishlist successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove property from wishlist',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Check if a property is in the user's favorites.
     */
    public function check(Request $request, $propertyId): JsonResponse
    {
        $user = Auth::user();
        
        $isFavorited = Favorite::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }
}
