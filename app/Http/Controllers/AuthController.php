<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(UserRegisterRequest $request)
    {
        $user = $this->userRepository->create($request->validated());
        event(new Registered($user));
        $verificationUrl = $this->createVerificationUrl($user);

        return (new UserResource($user))->additional(['verify_link' => $verificationUrl]);
    }

    public function login(UserLoginRequest $request)
    {
        if (auth()->attempt($request->validated())) {
            return new UserResource(auth()->user());
        }
        throw ValidationException::withMessages([
            'message' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'You have been successfully logged out.']);
    }
    private function createVerificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
    public function verifyEmail(Request $request)
    {
        $user = $this->userRepository->show(User::findOrFail($request->route('id')));
        $this->userRepository->verifyEmail($user, $request->route('hash'));

        return response()->json(['message' => 'Email verified successfully!']);
    }
}
