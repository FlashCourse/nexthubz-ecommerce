<div class="p-8 rounded-md bg-white">
    <!-- Rating Summary -->
    @php
        $averageRating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;
        $totalReviews = $reviews->count();
    @endphp

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground mb-2">Average rating</h2>
        <div class="flex items-center mb-2">
            <!-- Display Average Rating with Stars -->
            <span class="text-3xl font-bold text-foreground mr-4">{{ number_format($averageRating, 1) }}</span>
            <div class="flex items-center text-warning">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $averageRating)
                        <i class="fas fa-star text-yellow-500"></i>
                    @elseif ($i - $averageRating < 1)
                        <i class="fas fa-star-half-alt text-yellow-500"></i>
                    @else
                        <i class="far fa-star text-gray-400"></i> <!-- Empty star -->
                    @endif
                @endfor
            </div>
        </div>
        <p class="text-muted">{{ $totalReviews }} reviews</p>
    </div>

    <!-- Display Form for Creating a Review -->
    <form wire:submit.prevent="updateOrCreateReview" class="mb-4">
        <div class="mb-2">
            <label for="rating" class="block font-semibold text-foreground">Rating:</label>
            <select wire:model="rating" id="rating" required
                class="w-full border border-muted rounded focus:ring-primary focus:border-primary">
                <option value="">Select a rating</option>
                <option value="1">1 - Very Poor</option>
                <option value="2">2 - Poor</option>
                <option value="3">3 - Average</option>
                <option value="4">4 - Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="comment" class="block font-semibold text-foreground">Comment:</label>
            <textarea wire:model="comment" id="comment" rows="3"
                class="w-full px-3 py-2 border border-muted rounded focus:ring-primary focus:border-primary"></textarea>
        </div>
        <div>
            @auth
                <button type="submit"
                    class="px-4 py-2 font-semibold text-white bg-primary rounded hover:bg-secondary transition duration-300 ease-in-out">
                    @if ($userReview)
                        Update Review
                    @else
                        Submit Review
                    @endif
                </button>
            @else
                <a class="px-4 py-2 font-semibold text-white bg-primary rounded hover:bg-secondary transition duration-300 ease-in-out"
                    href="{{ route('login') }}">Login to add review</a>
            @endauth
        </div>
    </form>

    <!-- Display Current User's Review -->
    @if ($userReview)
        <div class="p-4 mb-4 bg-background rounded-md border border-muted">
            <div class="flex justify-between items-start">
                <!-- User name and rating -->
                <div class="mr-4">
                    <h3 class="text-lg font-semibold text-foreground">{{ $userReview->user->name }}</h3>
                    <div class="flex items-center">
                        <!-- Enhanced Star Rating -->
                        <div class="flex items-center text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $userReview->rating)
                                    <i class="fas fa-star text-yellow-500"></i>
                                @else
                                    <i class="far fa-star text-gray-400"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <!-- User comment -->
                    <p class="mt-2 text-gray-600">{{ $userReview->comment }}</p>
                </div>
                <!-- Delete button -->
                <button wire:click="deleteReview({{ $userReview->id }})"
                    class="text-danger hover:text-red-700 focus:outline-none">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Display Existing Reviews or Show "No Reviews Yet" Message -->
    @if ($reviews->isEmpty())
        <div
            class="flex flex-col items-center justify-center text-center py-12 bg-background rounded-md border border-muted">
            <i class="fas fa-info-circle text-muted mb-4" style="font-size: 3rem;"></i>
            <span class="text-lg text-gray-600 font-medium">No reviews yet. Be the first to write a review!</span>
        </div>
    @else
        <ul>
            @foreach ($reviews as $review)
                @if (!$userReview || $review->id !== $userReview->id)
                    <li class="p-4 mb-4 bg-background rounded-md border border-muted">
                        <div class="flex items-start">
                            <!-- User name and rating -->
                            <div class="mr-4">
                                <h3 class="text-lg font-semibold text-foreground">{{ $review->user->name }}</h3>
                                <div class="flex items-center">
                                    <!-- Enhanced Star Rating -->
                                    <div class="flex items-center text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fas fa-star text-yellow-500"></i>
                                            @else
                                                <i class="far fa-star text-gray-400"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <!-- User comment -->
                                <p class="mt-2 text-gray-600">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>
