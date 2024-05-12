<x-app-layout>
    <section class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-lg bg-white p-8 rounded-lg shadow-md text-center">
            <i class="fas fa-exclamation-triangle text-4xl text-orange-600 mb-4"></i>
            <h2 class="text-3xl font-bold text-orange-600 mb-4">Order Failed</h2>
            <p class="text-lg text-gray-700 mb-8">Sorry, your payment could not be processed successfully. Please try again later.</p>
            <div class="flex justify-center">
                <a href="{{ route('home') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded">Back to Home</a>
            </div>
        </div>
    </section>
</x-app-layout>
