<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;

interface UserRepositoryInterface
{
    public function index();
    public function show(User $user);
    public function create(array $data);
    public function update(User $user, array $data);
    public function delete(User $user);
    public function verifyEmail(User $user, $hash);

}

class UserRepository implements UserRepositoryInterface
{
    public function index()
    {
        return User::all();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();
    }
    public function verifyEmail(User $user, $hash)
    {
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
