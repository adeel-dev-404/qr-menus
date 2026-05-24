{{-- resources/views/dashboard/home.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-5xl">

    {{-- Welcome Banner --}}
    <div class="bg-blue-600 rounded-xl p-6 text-white mb-6">
        <h2 class="text-xl font-bold">Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p class="text-blue-200 mt-1">Here's what's happening with {{ $restaurant->name }} today.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('dashboard.products.index') }}"
           class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md transition">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['products'] }}</p>
            <p class="text-gray-500 mt-1 text-sm">Products</p>
        </a>
        <a href="{{ route('dashboard.categories.index') }}"
           class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md transition">
            <p class="text-3xl font-bold text-green-600">{{ $stats['categories'] }}</p>
            <p class="text-gray-500 mt-1 text-sm">Categories</p>
        </a>
        <a href="{{ route('dashboard.qr-codes.index') }}"
           class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md transition">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['qr_codes'] }}</p>
            <p class="text-gray-500 mt-1 text-sm">QR Codes</p>
        </a>
        <a href="{{ route('dashboard.branches.index') }}"
           class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md transition">
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['branches'] }}</p>
            <p class="text-gray-500 mt-1 text-sm">Branches</p>
        </a>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('dashboard.categories.create') }}"
               class="flex flex-col items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition text-sm text-gray-600 hover:text-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Category
            </a>
            <a href="{{ route('dashboard.products.create') }}"
               class="flex flex-col items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-green-400 hover:bg-green-50 transition text-sm text-gray-600 hover:text-green-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
            <a href="{{ route('dashboard.qr-codes.create') }}"
               class="flex flex-col items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition text-sm text-gray-600 hover:text-purple-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Generate QR
            </a>
            @if($restaurant)
            <a href="{{ url('/r/' . $restaurant->slug) }}" target="_blank"
               class="flex flex-col items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-yellow-400 hover:bg-yellow-50 transition text-sm text-gray-600 hover:text-yellow-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Menu ↗
            </a>
            @endif
        </div>
    </div>

</div>
@endsection