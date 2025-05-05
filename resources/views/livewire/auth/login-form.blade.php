<div class="lg:w-full w-full">
    <div class="w-full mx-auto space-y-6">
        <form wire:submit.prevent="login">
            <div class="relative mb-6 group">
                <label
                    class="absolute left-4 top-2 text-blue-600 text-sm font-medium transition-all duration-200 group-focus-within:text-[#3085FE]">
                    Username
                </label>
                <div class="flex items-center">
                    <div class="absolute left-4 bottom-4 text-gray-400 group-focus-within:text-[#3085FE]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="username"
                        class="w-full h-[65px] pl-12 pr-4 pt-7 pb-2 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                </div>
            </div>

            <div class="relative mb-6 group" x-data="{ showPassword: false }">
                <label
                    class="absolute left-4 top-2 text-blue-600 text-sm font-medium transition-all duration-200 group-focus-within:text-[#3085FE]">
                    Password
                </label>
                <div class="flex items-center">
                    <div class="absolute left-4 bottom-4 text-gray-400 group-focus-within:text-[#3085FE]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.live="password"
                        class="w-full h-[65px] pl-12 pr-12 pt-7 pb-2 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    <button type="button" x-on:click="showPassword = !showPassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#3085FE] transition-colors duration-200">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                            </path>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                            <line x1="2" x2="22" y1="2" y2="22"></line>
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="remember"
                        class="w-4 h-4 text-[#3085FE] border-gray-300 rounded focus:ring-[#3085FE]">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}" class="text-[#3085FE] text-sm hover:underline">
                        Forgot Password?
                    </a>
                </div>
            </div> --}}

            <button type="submit"
                class="w-full h-[60px] bg-[#3085FE] text-white rounded-xl text-lg font-medium transition-all duration-300 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2 group">
                <span>Login</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="transform transition-transform duration-300 group-hover:translate-x-1">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </button>
        </form>

        {{-- <div class="relative my-6">
            <hr class="border-gray-200">
            <span
                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-4 text-sm text-gray-400">
                Or continue with
            </span>
        </div>

        <div class="flex gap-4">
            <button type="button"
                class="flex-1 h-[50px] flex items-center justify-center gap-2 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#FFC107"
                        d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />
                    <path fill="#FF3D00"
                        d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" />
                    <path fill="#4CAF50"
                        d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />
                    <path fill="#1976D2"
                        d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />
                </svg>
                <span class="text-gray-600">Google</span>
            </button>
            <button type="button"
                class="flex-1 h-[50px] flex items-center justify-center gap-2 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
                    <path fill="#1877F2"
                        d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                </svg>
                <span class="text-gray-600">Facebook</span>
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">
                Don't have an account?
                <a href="#" class="text-[#3085FE] hover:underline font-medium">Sign up</a>
            </p>
        </div> --}}
    </div>

    <x-toast />
</div>
