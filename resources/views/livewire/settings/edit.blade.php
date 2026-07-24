<x-layouts.app title="Settings">
    <div class="max-w-3xl mx-auto py-10 space-y-10">

        <h1 class="text-2xl font-bold">Settings</h1>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif

        {{-- PROFILE PHOTO & NAME --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow space-y-4">
            <h2 class="font-semibold mb-4">Profile Information</h2>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('PATCH')

                <div class="flex items-center gap-4">
                    <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                        alt="Profile" class="h-16 w-16 rounded-full object-cover">
                    <input type="file" name="photo" class="dark:bg-zinc-900 p-2 border rounded">
                </div>

                <input type="text" name="name" value="{{ auth()->user()->name }}"
                    class="w-full p-2 border rounded dark:bg-zinc-900" placeholder="Your Name">

                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update Profile</button>
            </form>
        </div>

        {{-- CHANGE PASSWORD --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow">
            <h2 class="font-semibold mb-4">Change Password</h2>
            <form method="POST" action="{{ route('user-password.update') }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <input type="password" name="current_password" placeholder="Current password"
                    class="w-full p-2 border rounded dark:bg-zinc-900">
                <input type="password" name="password" placeholder="New password"
                    class="w-full p-2 border rounded dark:bg-zinc-900">
                <input type="password" name="password_confirmation" placeholder="Confirm new password"
                    class="w-full p-2 border rounded dark:bg-zinc-900">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update Password</button>
            </form>
        </div>

        {{-- THEME TOGGLE --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow">
            <h2 class="font-semibold mb-4">Appearance</h2>
            <form method="POST" action="{{ route('appearance.update') }}">
                @csrf
                <select name="theme" class="w-full p-2 border rounded dark:bg-zinc-900">
                    <option value="light" {{ auth()->user()->theme === 'light' ? 'selected' : '' }}>Light Mode</option>
                    <option value="dark" {{ auth()->user()->theme === 'dark' ? 'selected' : '' }}>Dark Mode</option>
                </select>
                <button class="px-4 py-2 bg-blue-600 text-white rounded mt-2">Save Theme</button>
            </form>
        </div>

        {{-- LANGUAGE SWITCH --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow">
            <h2 class="font-semibold mb-4">Language</h2>
            <form method="POST" action="{{ route('language.edit') }}">
                @csrf
                <select name="language" class="w-full p-2 border rounded dark:bg-zinc-900">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                    <option value="sw" {{ app()->getLocale() === 'sw' ? 'selected' : '' }}>Swahili</option>
                </select>
                <button class="px-4 py-2 bg-blue-600 text-white rounded mt-2">Save Language</button>
            </form>
        </div>

        {{-- DELETE ACCOUNT --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow">
            <h2 class="font-semibold mb-4 text-red-600">Delete Account</h2>
            <form method="POST" action="{{ route('account.delete') }}">
                @csrf
                @method('DELETE')
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                    Deleting your account is permanent. All your data will be lost.
                </p>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                    Delete Account
                </button>
            </form>
        </div>

        {{-- CONTACT INFO --}}
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow">
            <h2 class="font-semibold mb-4">Contact Support</h2>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                Email: <a href="mailto:aanatorius@gmail.com" class="text-blue-600">aanatorius@gmail.com</a>
            </p>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                WhatsApp: <a href="https://wa.me/255775611999" target="_blank" class="text-green-600">0775 611 999</a>
            </p>
        </div>

    </div>
</x-layouts.app>
