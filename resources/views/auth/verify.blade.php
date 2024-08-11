<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Your Text Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background: #f0f4f8;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .illustration {
            text-align: center;
            margin-bottom: 1rem;
        }
        .illustration img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="bg-blue-50 flex items-center justify-center h-screen">
    <div class="container">
        <h2 class="text-xl font-semibold mb-4">Check your text messages</h2>
        <div class="illustration">
            <img src="/path/to/your/image.png" alt="Illustration">
        </div>
        <form action="{{ route('verify.code') }}" method="POST">
            @csrf
            <input type="text" name="code" placeholder="Code" class="w-full px-4 py-2 border rounded mb-4" required>
            <div class="flex items-center justify-between mb-6">
                <button type="button" class="text-blue-500">Send again</button>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Continue</button>
        </form>
        <button class="w-full mt-4 text-blue-500 py-2 rounded border border-blue-500">Try another way</button>
    </div>
</body>

</html>