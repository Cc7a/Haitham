<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function index(): View
    {
        $members = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'last_login_at', 'login_count'])
            ->latest('created_at')
            ->paginate(20);

        return view('owner.index', [
            'members' => $members,
            'summary' => [
                'members' => User::count(),
                'logins' => User::sum('login_count'),
                'latestLogin' => User::query()
                    ->whereNotNull('last_login_at')
                    ->latest('last_login_at')
                    ->value('last_login_at'),
            ],
        ]);
    }
}