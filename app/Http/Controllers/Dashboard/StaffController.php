<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('restaurant_id', auth()->user()->restaurant_id)
            ->where('id', '!=', auth()->id())
            ->get();

        return view('dashboard.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('dashboard.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:restaurant_owner,restaurant_staff',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'restaurant_id' => auth()->user()->restaurant_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('dashboard.staff.index')
            ->with('success', 'Staff member added.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        abort_if($user->id === auth()->id(), 403);
        // Prevent deleting staff from other restaurants
        abort_if($user->restaurant_id !== auth()->user()->restaurant_id, 403);

        $user->delete();

        return redirect()->route('dashboard.staff.index')
            ->with('success', 'Staff member removed.');
    }
}