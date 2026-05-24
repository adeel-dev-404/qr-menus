{{-- resources/views/auth/invite-accept.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invite — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">

    <div class="text-center mb-6">
        <span class="text-4xl">🍽</span>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Set Your Password</h1>
        <p class="text-gray-500 text-sm mt-1">
            Welcome, <strong>{{ $user->name }}</strong>!<br>
            Choose a password to activate your account.
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 rounded-lg p-3 mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('invite.accept.store', $token) }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <input type="email" value="{{ $user->email }}" disabled
                   class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-500 cursor-not-allowed">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                New Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" required minlength="8"
                   placeholder="Minimum 8 characters"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2
                          focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Confirm Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation" required
                   placeholder="Repeat your password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2
                          focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold
                       py-2 rounded-lg transition mt-2">
            Activate Account & Go to Dashboard →
        </button>
    </form>
</div>
</body>
</html>