@extends('layouts.dashboard')
@section('page-title', 'Staff')
@section('content')

<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Staff Members</h2>
        <a href="{{ route('staff.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Staff
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Joined</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($staff as $member)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        {{ $member->name }}
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $member->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $member->hasRole('restaurant_owner') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $member->hasRole('restaurant_owner') ? 'Owner' : 'Staff' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $member->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <form method="POST" action="{{ route('staff.destroy', $member) }}"
                              onsubmit="return confirm('Remove this staff member?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                        No staff members yet.
                        <a href="{{ route('staff.create') }}" class="text-blue-600">Add one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection