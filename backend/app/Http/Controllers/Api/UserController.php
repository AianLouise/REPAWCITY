<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Update the authenticated user's profile (fname/lname/email).
     * Ported from user/edit-profile.php, but updates by authenticated id
     * (fixes the legacy bug of updating by email).
     */
    public function updateProfile(UpdateProfileRequest $request): UserResource
    {
        $user = $request->user();

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->save();

        return new UserResource($user->fresh());
    }

    /**
     * Change the authenticated user's password.
     * Ported from user/change-password.php. Legacy plain-text passwords are
     * handled automatically because seed data is now always hashed.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Old password does not match'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * List all users (ported from admin/admin-manage-user.php).
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::orderBy('id')->get());
    }

    /**
     * Update a user's name/email/optional password (ported from admin/admin-manage-user.php).
     */
    public function update(AdminUpdateUserRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return new UserResource($user->fresh());
    }

    /**
     * Delete a user (ported from admin/admin-manage-user.php).
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }

    /**
     * Promote (2 -> 1) or demote (1 -> 2) a user (ported from admin/admin-manage-user.php).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): UserResource
    {
        $user->update(['user_type' => $request->user_type]);

        return new UserResource($user->fresh());
    }
}
