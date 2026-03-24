<x-guest-layout>

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">

        <!-- 🔥 Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-black via-zinc-900 to-black"></div>

        <!-- 🔥 Animated Blobs -->
        <div class="absolute top-[-100px] left-[-100px] w-[500px] h-[500px] bg-red-600/20 blur-3xl rounded-full animate-blob1"></div>
        <div class="absolute bottom-[-120px] right-[-80px] w-[400px] h-[400px] bg-purple-600/20 blur-3xl rounded-full animate-blob2"></div>
        <div class="absolute top-[30%] left-[60%] w-[450px] h-[450px] bg-blue-600/20 blur-3xl rounded-full animate-blob3"></div>

        <!-- 🎥 Light Sweep -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="w-full h-full bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sweep"></div>
        </div>

        <!-- 🔥 Card -->
        <div class="relative z-10 w-full max-w-md p-8 
        bg-zinc-900/70 backdrop-blur-2xl 
        rounded-2xl border border-zinc-700
        shadow-[0_0_80px_rgba(255,0,0,0.15)]
        animate-float">

            <!-- Heading -->
            <h2 class="text-3xl font-bold text-white mb-2 text-center">
                Forgot Password 🔐
            </h2>

            <p class="text-gray-400 text-center mb-6 text-sm">
                Enter your email to receive a reset link
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-500 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" value="Email" class="text-gray-400" />

                    <x-text-input id="email"
                        class="block mt-1 w-full bg-zinc-800/80 text-white border border-zinc-700 rounded-lg
                    focus:ring-2 focus:ring-red-600 focus:shadow-[0_0_20px_rgba(255,0,0,0.4)]"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus />

                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg text-white font-semibold transition">
                    Send Reset Link
                </button>

            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-gray-400 text-sm space-y-2">

                <p>
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-red-500 hover:underline">
                        Login
                    </a>
                </p>

            </div>

        </div>

    </div>

</x-guest-layout>