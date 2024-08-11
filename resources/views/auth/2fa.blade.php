<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
</head>
<body>
    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <label for="code">Enter the code sent to your phone:</label>
        <input type="text" name="code" id="code" required>
        @error('code')
            <div>{{ $message }}</div>
        @enderror
        <button type="submit">Verify</button>
    </form>
</body>
</html>
