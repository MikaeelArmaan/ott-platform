<x-admin-layout>

<div class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-black via-zinc-900 to-black"></div>

    <!-- Animated Blobs -->
    <div class="absolute top-[-100px] left-[-100px] w-[500px] h-[500px] bg-red-600/20 blur-3xl rounded-full animate-blob1"></div>
    <div class="absolute bottom-[-120px] right-[-80px] w-[400px] h-[400px] bg-purple-600/20 blur-3xl rounded-full animate-blob2"></div>
    <div class="absolute top-[30%] left-[60%] w-[450px] h-[450px] bg-blue-600/20 blur-3xl rounded-full animate-blob3"></div>

    <!-- Light Sweep -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="w-full h-full bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sweep"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md p-8 
        bg-zinc-900/70 backdrop-blur-2xl 
        rounded-2xl border border-zinc-700
        shadow-[0_0_80px_rgba(255,0,0,0.15)]
        animate-float">

        <!-- Heading -->
        <h2 class="text-3xl font-bold text-white mb-2 text-center">
            Admin Login ⚙️
        </h2>

        <p class="text-gray-400 text-center mb-6 text-sm">
            Secure access to dashboard
        </p>

        <!-- Session -->
        <x-auth-session-status class="mb-4 text-green-500" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <input name="email" type="email" value="{{ old('email') }}"
                placeholder="Email address"
                class="w-full p-3 bg-zinc-800/80 text-white rounded-lg border border-zinc-700 
                focus:ring-2 focus:ring-red-600 
                focus:shadow-[0_0_20px_rgba(255,0,0,0.4)] transition-all">

            <!-- Password -->
            <input name="password" type="password"
                placeholder="Password"
                class="w-full p-3 bg-zinc-800/80 text-white rounded-lg border border-zinc-700 
                focus:ring-2 focus:ring-red-600 
                focus:shadow-[0_0_20px_rgba(255,0,0,0.4)] transition-all">

            <!-- Actions -->
            <div class="flex items-center justify-between text-sm text-gray-400">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="accent-red-600">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="hover:text-red-500">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold transition">
                Sign In
            </button>

        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-400 text-sm space-y-2">

            <p>
                <a href="/login" class="hover:text-white">
                    ← Consumer Login
                </a>
            </p>

        </div>

    </div>

</div>

</x-admin-layout>