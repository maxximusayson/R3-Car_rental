<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Reservation Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        @media print {
            @page {
                size: A4;
            }
        }

        body {
            font-family: "Inter", sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .inv-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .inv-header,
        .inv-footer {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .inv-body {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .thank-you {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="inv-title">
            <h2>Reservation Receipt</h2>
        </div>
        <div class="inv-header">
            <h4>R3 Garage Car Rental</h4>
            <p>0955 379 3727 | r3garage05@gmail.com</p>
        </div>
        <div class="inv-body">
            <table>
                <tr>
                    <th>Car</th>
                    <td>{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{ $reservation->start_date }}</td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>{{ $reservation->end_date }}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{{ $reservation->days }} days</td>
                </tr>
                <tr>
                    <th>Price per day</th>
                    <td>{{ $reservation->price_per_day }} ₱</td>
                </tr>
                <tr>
                    <th>Reservation Price</th>
                    <td>{{ $reservation->total_price }} ₱</td>
                </tr>
                <tr>
                    <th>Mode of Payment</th>
                    <td>{{ $reservation->payment_method }}</td>
                </tr>
            </table>
        </div>
        <div class="inv-footer">
            <table>
                <tr>
                    <th>Subtotal</th>
                    <td>{{ $reservation->total_price }} ₱</td>
                </tr>
                <tr>
                    <th>Tax (15%)</th>
                    <td>{{ intval($reservation->total_price * 0.15) }} ₱</td>
                </tr>
                <tr>
                    <th style="color: #ff9b00">Total to Pay</th>
                    <td>{{ $reservation->total_price + intval($reservation->total_price * 0.15) }} ₱</td>
                </tr>
            </table>
        </div>
        <div class="thank-you">
            <p>Thank you for choosing and trusting our car company!</p>
        </div>
    </div>
</body>

</html>
