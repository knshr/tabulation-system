<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(UserService $service): Response
    {
        return Inertia::render('Users/Index', [
            'users' => $service->getAllUsers(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request, UserService $service)
    {
        $service->createUser($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        $service->updateUser($user->id, $request->validated());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user, UserService $service)
    {
        $service->deleteUser($user->id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
