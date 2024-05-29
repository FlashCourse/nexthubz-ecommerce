<x-app-layout>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script id="myScript" src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js">
    </script>


    <section class="px-4">
        <div class="container mx-auto py-8">
            <h1 class="text-3xl text-center font-semibold mb-4">Review Order Items</h1>

            <div class="grid grid-cols-1 xl:grid-cols-6 gap-5">
                <!-- Cart Items -->
                <div class="bg-white shadow-md xl:col-span-4 rounded-lg">
                    <h2 class="text-lg font-semibold border-b border-gray-200 py-4 px-6">Cart Items</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-4 px-6 text-left">Item</th>
                                    <th class="py-4 px-6 text-left">Unit Price</th>
                                    <th class="py-4 px-6 text-left">Quantity</th>
                                    <th class="py-4 px-6 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartData as $cartItem)
                                    <tr class="border-b border-gray-200">
                                        <td class="py-4 px-6">{{ $cartItem['name'] }} @if (isset($cartItem['variant_attributes']) && is_array($cartItem['variant_attributes']))
                                                <div class="flex flex-wrap gap-1 mt-1 text-xs">
                                                    @foreach ($cartItem['variant_attributes'] as $attribute => $value)
                                                        <span
                                                            class="inline-block bg-gray-200 text-gray-800 py-1 px-2 rounded">{{ $attribute }}:
                                                            {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">${{ number_format($cartItem['price'], 2) }}</td>
                                        <td class="py-4 px-6">{{ $cartItem['quantity'] }}</td>
                                        <td class="py-4 px-6">
                                            ${{ number_format($cartItem['price'] * $cartItem['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="xl:col-span-2 p-4 bg-orange-100">
                    <!-- Order Summary -->
                    <div class="rounded-lg mb-8">
                        <h2 class="text-lg font-semibold border-b border-gray-200 py-4 px-6">Order Summary</h2>
                        <div class="grid grid-cols-2 gap-4 px-6 py-4">
                            <div class="text-gray-600">Subtotal:</div>
                            <div class="text-right">${{ number_format($orderData['subtotal'], 2) }}</div>
                            <div class="text-gray-600">Tax:</div>
                            <div class="text-right">${{ number_format($orderData['tax'], 2) }}</div>
                            <div class="text-gray-600">Shipping:</div>
                            <div class="text-right">${{ number_format($orderData['shipping'], 2) }}</div>
                            <div class="text-xl font-semibold">Total:</div>
                            <div class="text-xl font-semibold text-right">${{ number_format($orderData['total'], 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Button -->
                    <div class="text-center">
                        <x-button id="bKash_button" onclick="BkashPayment()">
                            Confirm
                        </x-button>
                    </div>
                </div>

            </div>
        </div>
    </section>




    <script type="text/javascript">
        // CSRF token setup for Ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function BkashPayment() {
            // Show loading or any indicator to the user
            Swal.fire({
                title: 'Please wait...',
                text: 'We are processing payment',
                willOpen: () => {
                    // Your logic here
                    console.log('Before the alert is opened');
                }
            });


            // Get token
            $.ajax({
                url: "{{ route('bkash-get-token') }}",
                type: 'POST',
                contentType: 'application/json',
                success: function(data) {
                    if (data.hasOwnProperty('msg')) {
                        showErrorMessage(data); // handle unknown error
                    } else {
                        $('#pay-with-bkash-button').trigger('click');
                    }
                },
                error: function(err) {
                    showErrorMessage(err);
                }
            });
        }

        let paymentID = '';
        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: {},
            createRequest: function(request) {
                createPayment(request);
            },

            executeRequestOnAuthorization: function(request) {
                $.ajax({
                    url: '{{ route('bkash-execute-payment') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        "paymentID": paymentID
                    }),
                    success: function(data) {
                        if (data && data.paymentID) {
                            BkashSuccess(data);
                        } else {
                            showErrorMessage(data);
                            bKash.execute().onError();
                        }
                    },
                    error: function(err) {
                        bKash.execute().onError();
                        showErrorMessage(err.responseJSON);
                    }
                });
            },
            onClose: function() {
                // handle close event if needed
                Swal.fire({
                    title: 'Payment Canceled',
                    text: 'You have canceled the payment process.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });

        function createPayment(request) {
            $.ajax({
                url: '{{ route('bkash-create-payment') }}',
                data: JSON.stringify(request),
                type: 'POST',
                contentType: 'application/json',
                success: function(data) {
                    Swal.close(); // close the loading indicator

                    if (data && data.paymentID) {
                        paymentID = data.paymentID;
                        bKash.create().onSuccess(data);
                    } else {
                        bKash.create().onError();
                    }
                },
                error: function(err) {
                    showErrorMessage(err.responseJSON);
                    bKash.create().onError();
                }
            });
        }

        function BkashSuccess(data) {
            $.post('{{ route('bkash-success') }}', {
                payment_info: data
            }, function(res) {
                location.reload();
            });
        }

        function showErrorMessage(response) {
            let message = 'Unknown Error';

            if (response && response.hasOwnProperty('errorMessage')) {
                message = response.errorMessage;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire("Payment Failed!", message, "error");
            } else {
                console.error("Payment Failed!", message);
            }
        }
    </script>

</x-app-layout>
