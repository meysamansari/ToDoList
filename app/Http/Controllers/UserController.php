<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userRepository->index();
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validated();

        $user = $this->userRepository->create($validatedData);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validatedData = $request->validated();
        $updatedUser = $this->userRepository->update($user, $validatedData);
        return new UserResource($updatedUser);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userRepository->delete($user);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
