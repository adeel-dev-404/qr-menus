@extends('layouts.dashboard')
@section('page-title', 'Edit Category')
@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('dashboard.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="w-full border rounded-lg px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                @if($category->getFirstMediaUrl('image'))
                    <img src="{{ $category->image_url }}" class="w-20 h-20 rounded-lg object-cover mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6 flex items-center gap-2">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" id="status"
                       {{ old('status', $category->status) ? 'checked' : '' }}>
                <label for="status" class="text-sm text-gray-700">Active</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Category
                </button>
                <a href="{{ route('dashboard.categories.index') }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection