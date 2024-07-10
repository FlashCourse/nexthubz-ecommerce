<div class="p-8  rounded-md">
    <!-- Display Form for Creating a Review -->
    <form wire:submit.prevent="updateOrCreateReview" class="mb-4">
        <div class="mb-2">
            <label for="rating" class="block font-semibold">Rating:</label>
            <x-input type="number" wire:model="rating" id="rating" min="1" max="5" required
                class="w-full" />
        </div>
        <div class="mb-2">
            <label for="comment" class="block font-semibold">Comment:</label>
            <textarea wire:model="comment" id="comment" rows="3"
                class="w-full focus:ring-orange-500 focus:border-orange-500 px-3 py-2 border border-gray-300 rounded"></textarea>
        </div>
        <div>
            @auth
                <button type="submit" class="px-4 py-2 font-semibold text-white bg-orange-500 rounded hover:bg-orange-600">
                    @if ($userReview)
                        Update Review
                    @else
                        Submit Review
                    @endif
                </button>
            @else
                <a class="px-4 py-2 font-semibold text-white bg-orange-500 rounded hover:bg-orange-600"
                    href="{{ route('login') }}">Login to add review</a>
            @endauth
        </div>
    </form>

    <!-- Display Current User's Review -->
    @if ($userReview)
        <div class="p-4 mb-4 bg-white rounded-md border">
            <div class="flex items-center justify-between">
                <!-- User name and rating -->
                <div>
                    <h3 class="text-lg font-semibold">{{ $userReview->user->name }}</h3>
                    <div class="flex items-center">
                        <span class="text-lg font-semibold mr-1">{{ $userReview->rating }}</span>
                        <div class="flex items-center text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $userReview->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
                <!-- Delete button -->
                <button wire:click="deleteReview({{ $userReview->id }})"
                    class="text-red-500 hover:text-red-700 focus:outline-none">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <!-- User comment -->
            <p class="mt-2 text-gray-700">{{ $userReview->comment }}</p>
        </div>

    @endif



    <!-- Display Existing Reviews -->
    <ul>
        @foreach ($reviews as $review)
            @if (!$userReview || $review->id !== $userReview->id)
                <div class="p-4 mb-4 bg-white rounded-md border">
                    <div class="flex items-center justify-between">
                        <!-- User rating -->
                        <div class="flex items-center">
                            <span class="text-lg font-semibold">{{ $review->rating }}</span>
                            <div class="flex items-center text-yellow-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <!-- User comment -->
                    <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
                </div>
            @endif
        @endforeach
    </ul>
</div>
