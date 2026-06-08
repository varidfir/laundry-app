<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div style="position: relative; margin-top: 0.5rem;">
                <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" style="padding-right: 40px;" />
                <button type="button" onclick="togglePasswordReset()" 
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 5px 8px;">
                    <i class="bi bi-eye" id="resetPasswordIcon" style="font-size: 18px;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div style="position: relative; margin-top: 0.5rem;">
                <x-text-input id="password_confirmation" class="block w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" style="padding-right: 40px;" />
                <button type="button" onclick="togglePasswordResetConfirm()" 
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 5px 8px;">
                    <i class="bi bi-eye" id="resetConfirmPasswordIcon" style="font-size: 18px;"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

    <style>
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
    </style>

    <script>
        function togglePasswordReset() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('resetPasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }

        function togglePasswordResetConfirm() {
            const passwordInput = document.getElementById('password_confirmation');
            const passwordIcon = document.getElementById('resetConfirmPasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }
    </script>
</x-guest-layout>
