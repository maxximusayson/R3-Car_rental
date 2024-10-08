@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PayPal JS SDK Standard Integration</title>
</head>
<body>
    <div id="paypal-button-container"></div>
    <p id="result-message"></p>

    <!-- Initialize the JS-SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD&components=buttons&enable-funding=venmo"></script>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch('{{ route("paypal.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                    },
                    body: JSON.stringify({
                        // Add your order details here (e.g., total amount, items, etc.)
                        items: [
                            {
                                id: 'YOUR_PRODUCT_ID',
                                quantity: 1
                            }
                        ]
                    })
                }).then(function(response) {
                    return response.json();
                }).then(function(orderData) {
                    return orderData.id; // Return the order ID for the next step
                }).catch(function(error) {
                    console.error('Error creating order:', error);
                    document.getElementById('result-message').innerText = 'Error creating order';
                });
            },
            onApprove: function(data, actions) {
                // Handle the approval of the payment here
                window.location.href = '{{ route("paypal.success") }}'; // Redirect to a success page after approval
            },
            onCancel: function(data) {
                // Handle the cancellation of the payment
                window.location.href = '{{ route("paypal.cancel") }}'; // Redirect to a cancellation page
            }
        }).render('#paypal-button-container');
    </script>
    
</body>
</html>

@endsection
