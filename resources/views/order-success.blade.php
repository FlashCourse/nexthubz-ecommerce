<x-app-layout>
    <section class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-lg bg-white p-8 rounded-lg shadow-lg text-center">
            <div class="mb-6">
                <i class="fas fa-check-circle text-6xl text-orange-500 mb-4"></i>
                <h2 class="text-3xl font-bold text-orange-500 mb-2">Order Successful</h2>
                <p class="text-lg text-gray-700">Thank you for your purchase! Your order has been successfully placed.
                </p>
            </div>

            <div class="flex-col space-y-4 mb-6">
                <a href="{{ route('user.orders') }}"
                    class="inline-block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    View All Orders
                </a>
                <a href="{{ route('home') }}"
                    class="inline-block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Continue Shopping
                </a>
                <a href="{!! $signedUrl !!}" id="manualDownloadButton"
                    class="inline-block w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Download Invoice
                </a>
            </div>


        </div>
    </section>

    <script type="text/javascript">
        window.onload = function() {
            // Get the signed URL from the session
            var downloadUrl = "{!! $signedUrl !!}";

            // Automatically trigger the download
            var a = document.createElement('a');
            a.href = downloadUrl;
            a.download = 'order_invoice.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
        };
    </script>
</x-app-layout>
