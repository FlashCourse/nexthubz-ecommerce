<div class="px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-semibold text-center mb-8">Frequently Asked Questions</h2>

        <div x-data="{ openTab: 1 }" class="accordion">
            <!-- How do I create an account? -->
            <div class="border-b">
                <button @click="openTab !== 1 ? openTab = 1 : openTab = null"
                    class="accordion-title flex justify-between items-center bg-orange-200 hover:bg-orange-300 px-4 py-3 rounded-t-lg font-semibold w-full">
                    <span>Q: How do I create an account?</span>
                    <svg x-show="openTab !== 1" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 6l6 6H4z" />
                    </svg>
                    <svg x-show="openTab === 1" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 16l-6-6h12z" />
                    </svg>
                </button>
                <div x-show="openTab === 1" class="accordion-content py-2 px-4">
                    <p class="text-gray-600">A: To create an account, simply click on the "Sign Up" button located at
                        the top-right corner of the page and follow the instructions.</p>
                </div>
            </div>

            <!-- How long does delivery take? -->
            <div class="border-b">
                <button @click="openTab !== 2 ? openTab = 2 : openTab = null"
                    class="accordion-title flex justify-between items-center bg-orange-200 hover:bg-orange-300 px-4 py-3 font-semibold w-full">
                    <span>Q: How long does delivery take?</span>
                    <svg x-show="openTab !== 2" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 6l6 6H4z" />
                    </svg>
                    <svg x-show="openTab === 2" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 16l-6-6h12z" />
                    </svg>
                </button>
                <div x-show="openTab === 2" class="accordion-content py-2 px-4">
                    <p class="text-gray-600">A: Delivery times vary depending on your location and the items you've
                        ordered. Typically, orders are delivered within 2-5 business days.</p>
                </div>
            </div>

            <!-- How do I track my order? -->
            <div class="border-b">
                <button @click="openTab !== 3 ? openTab = 3 : openTab = null"
                    class="accordion-title flex justify-between items-center bg-orange-200 hover:bg-orange-300 px-4 py-3 font-semibold w-full">
                    <span>Q: How do I track my order?</span>
                    <svg x-show="openTab !== 3" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 6l6 6H4z" />
                    </svg>
                    <svg x-show="openTab === 3" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 16l-6-6h12z" />
                    </svg>
                </button>
                <div x-show="openTab === 3" class="accordion-content py-2 px-4">
                    <p class="text-gray-600">A: You can track your order by visiting the "Order Tracking" page on our
                        website and entering your order number and email address.</p>
                </div>
            </div>

            <!-- What payment methods do you accept? -->
            <div class="border-b">
                <button @click="openTab !== 4 ? openTab = 4 : openTab = null"
                    class="accordion-title flex justify-between items-center bg-orange-200 hover:bg-orange-300 px-4 py-3 font-semibold w-full">
                    <span>Q: What payment methods do you accept?</span>
                    <svg x-show="openTab !== 4" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 6l6 6H4z" />
                    </svg>
                    <svg x-show="openTab === 4" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 16l-6-6h12z" />
                    </svg>
                </button>
                <div x-show="openTab === 4" class="accordion-content py-2 px-4">
                    <p class="text-gray-600">A: We accept various payment methods including credit/debit cards, PayPal,
                        and bank transfers.</p>
                </div>
            </div>
            <!-- How can I return an item? -->
            <div class="border-b">
                <button @click="openTab !== 5 ? openTab = 5 : openTab = null"
                    class="accordion-title flex justify-between items-center bg-orange-200 hover:bg-orange-300 px-4 py-3 font-semibold w-full">
                    <span>Q: How can I return an item?</span>
                    <svg x-show="openTab !== 5" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 6l6 6H4z" />
                    </svg>
                    <svg x-show="openTab === 5" class="w-4 h-4 fill-current text-orange-600"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 16l-6-6h12z" />
                    </svg>
                </button>
                <div x-show="openTab === 5" class="accordion-content py-2 px-4">
                    <p class="text-gray-600">A: To return an item, please contact our customer support team within 30
                        days of receiving your order. They will provide you with instructions on how to proceed with the
                        return.</p>
                </div>
            </div>


        </div>
    </div>
</div>
