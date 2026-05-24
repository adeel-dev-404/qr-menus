@extends('layouts.dashboard')
@section('page-title', 'Add Staff')
@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-800 text-lg mb-6">Add Staff Member</h3>

        <form method="POST" action="{{ route('staff.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded-lg px-3 py-2 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                <input type="password" name="password"
                       class="w-full border rounded-lg px-3 py-2 @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                <input type="password" name="password_confirmation"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select name="role" class="w-full border rounded-lg px-3 py-2">
                    <option value="restaurant_staff"  {{ old('role') === 'restaurant_staff'  ? 'selected' : '' }}>Staff (limited access)</option>
                    <option value="restaurant_owner"  {{ old('role') === 'restaurant_owner'  ? 'selected' : '' }}>Owner (full access)</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Staff Member
                </button>
                <a href="{{ route('staff.index') }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection