<!-- checkout-selection.blade.php -->

<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-7xl mx-auto p-6">
            <h2 class="text-3xl font-extrabold mb-10 text-center text-gray-800">Select Checkout Type</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <!-- User Checkout Card -->
                <div
                    class="border border-gray-300 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-8 bg-white text-center transform hover:-translate-y-2">
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-user fa-4x text-primary"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">User Checkout</h3>
                    <p class="text-gray-600 mb-6">
                        Sign in or create an account for a faster checkout experience and order tracking.
                    </p>
                    <a href="{{ route('login') }}">
                        <button
                            class="bg-primary text-white font-bold py-2 px-6 rounded-full hover:bg-orange-700 transition-colors duration-200">
                            <i class="fas fa-arrow-right mr-2"></i> Continue as User
                        </button>
                    </a>
                </div>

                <!-- Guest Checkout Card -->
                <div
                    class="border border-gray-300 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-8 bg-white text-center transform hover:-translate-y-2">
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-user-secret fa-4x text-primary"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Guest Checkout</h3>
                    <p class="text-gray-600 mb-6">
                        Proceed without creating an account. You won’t be able to track your order online.
                    </p>
                    <a href="{{ route('checkout') }}">
                        <button
                            class="bg-primary text-white font-bold py-2 px-6 rounded-full hover:bg-orange-700 transition-colors duration-200">
                            <i class="fas fa-arrow-right mr-2"></i> Continue as Guest
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
