<?php

namespace App\Http\Controllers\Web;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request, EventService $eventService): Response
    {
        $user = $request->user();

        $data = [
            'user' => $user,
        ];

        if ($user->hasRole(UserRole::SuperAdmin, UserRole::Admin)) {
            $data['events'] = $eventService->getAllEvents();
        } elseif ($user->hasRole(UserRole::Judge)) {
            $data['events'] = $user->judgingEvents()->with('creator')->get();
        }

        return Inertia::render('Dashboard/Index', $data);
    }
}
