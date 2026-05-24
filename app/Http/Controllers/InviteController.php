<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    public function show(string $token)
    {
        $user = User::where('invite_token', $token)->firstOrFail();

        if ($user->hasAcceptedInvite()) {
            return redirect()->route('login')
                ->with('error', 'This invite link has already been used. Please log in.');
        }

        return view('auth.invite-accept', [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    public function store(Request $request, string $token)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::where('invite_token', $token)->firstOrFail();

        if ($user->hasAcceptedInvite()) {
            return redirect()->route('login')
                ->with('error', 'This invite has already been accepted.');
        }

        $user->update([
            'password'           => Hash::make($request->password),
            'invite_accepted_at' => now(),
            'invite_token'       => null, // invalidate so link can't be reused
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.home')
            ->with('success', 'Welcome! Your account is ready.');
    }
}