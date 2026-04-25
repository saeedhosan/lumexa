<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    public function show(Request $request, Invite $invite): \Illuminate\View\View
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'This invitation link is invalid or has expired.');
        }

        return view('invite.accept', ['invite' => $invite]);
    }

    public function accept(Request $request, Invite $invite): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $invite->email,
            'password' => Hash::make($request->password),
            'status'   => UserStatus::active,
            'type'     => UserType::customer,
        ]);

        $user->companies()->attach($invite->company_id, [
            'role' => $invite->role,
        ]);

        $invite->update(['accepted_at' => now()]);

        return redirect()->route('login')->with('toast', 'Account created! Please log in.');
    }
}
