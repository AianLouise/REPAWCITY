<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetFeaturedRequest;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    /**
     * List pets with optional filters (ported from pages/adoptpage.php +
     * includes/userfunction.php).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $pets = Pet::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('sex'), fn ($q) => $q->where('sex', $request->sex))
            ->when($request->filled('weight'), fn ($q) => $q->where('weight', $request->weight))
            ->when($request->filled('age'), fn ($q) => $q->where('age', $request->age))
            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', '>', 0)->orderBy('is_featured'))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub
                ->where('name', 'like', '%'.$request->q.'%')
                ->orWhere('breed', 'like', '%'.$request->q.'%')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 24));

        return PetResource::collection($pets);
    }

    /**
     * Show a single pet (ported from pages/adoptprofile.php).
     */
    public function show(Pet $pet): PetResource
    {
        return new PetResource($pet);
    }

    /**
     * Add a pet with image upload (ported from admin/admin-add-pets.php).
     */
    public function store(StorePetRequest $request, FileUploadService $uploader): JsonResponse
    {
        $image = $uploader->storeImage($request->file('image'), 'pets');

        $pet = Pet::create([
            ...$request->validated(),
            'image' => $image,
            'is_featured' => 0,
            'user_id' => $request->user()->id,
        ]);

        return (new PetResource($pet))->response()->setStatusCode(201);
    }

    /**
     * Update a pet (optional new image) (ported from admin/admin-manage-pets.php).
     */
    public function update(UpdatePetRequest $request, Pet $pet, FileUploadService $uploader): PetResource
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $uploader->deleteImage('pets', $pet->image);
            $data['image'] = $uploader->storeImage($request->file('image'), 'pets');
        }

        $pet->update($data);

        return new PetResource($pet->fresh());
    }

    /**
     * Delete a pet and its image (ported from admin/admin-manage-pets.php).
     */
    public function destroy(Pet $pet, FileUploadService $uploader): JsonResponse
    {
        $uploader->deleteImage('pets', $pet->image);
        $pet->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }

    /**
     * Assign the four featured pet slots (ported from admin/admin-manage-featured.php).
     * Clears all is_featured then sets slots 1-4 inside a transaction.
     */
    public function setFeatured(SetFeaturedRequest $request): JsonResponse
    {
        $ids = [
            $request->featured_image_1,
            $request->featured_image_2,
            $request->featured_image_3,
            $request->featured_image_4,
        ];

        DB::transaction(function () use ($ids) {
            Pet::query()->update(['is_featured' => 0]);

            foreach ($ids as $slot => $id) {
                Pet::whereKey($id)->update(['is_featured' => $slot + 1]);
            }
        });

        return response()->json(['message' => 'Records updated successfully']);
    }
}
