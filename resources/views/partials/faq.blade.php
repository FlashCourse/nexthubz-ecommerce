@php
    $faqs = [
        [
            'question' => 'How do I create an account?',
            'answer' =>
                'To create an account, simply click on the "Sign Up" button located at the top-right corner of the page and follow the instructions.',
            'tab' => 1,
        ],
        [
            'question' => 'How long does delivery take?',
            'answer' =>
                'Delivery times vary depending on your location and the items you\'ve ordered. Typically, orders are delivered within 2-5 business days.',
            'tab' => 2,
        ],
        [
            'question' => 'How do I track my order?',
            'answer' =>
                'You can track your order by visiting the "Order Tracking" page on our website and entering your order number and email address.',
            'tab' => 3,
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept various payment methods including credit/debit cards, PayPal, and bank transfers.',
            'tab' => 4,
        ],
        [
            'question' => 'How can I return an item?',
            'answer' =>
                'To return an item, please contact our customer support team within 30 days of receiving your order. They will provide you with instructions on how to proceed with the return.',
            'tab' => 5,
        ],
    ];
@endphp

<section class="px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-semibold text-primary text-center mb-4">Frequently Asked Questions</h2>
        <p class="text-xl text-center mb-8 text-muted">Got Questions? We've Got Answers!</p>

        <div x-data="{ openTab: 1 }" class="accordion text-light">
            @foreach ($faqs as $faq)
                <div class="border-b">
                    <button @click="openTab !== {{ $faq['tab'] }} ? openTab = {{ $faq['tab'] }} : openTab = null"
                        class="accordion-title flex justify-between items-center bg-primary hover:bg-secondary px-4 py-3 font-semibold w-full">
                        <span>Q: {{ $faq['question'] }}</span>
                        <svg x-show="openTab !== {{ $faq['tab'] }}" class="w-4 h-4 fill-current text-light"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 6l6 6H4z" />
                        </svg>
                        <svg x-show="openTab === {{ $faq['tab'] }}" class="w-4 h-4 fill-current text-light"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 16l-6-6h12z" />
                        </svg>
                    </button>
                    <div x-show="openTab === {{ $faq['tab'] }}" class="accordion-content py-2 px-4">
                        <p class="text-muted">A: {{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
