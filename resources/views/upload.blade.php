<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
</head>
<body>
    <h1>File Upload Form</h1>

    <!-- Display any success or error messages here -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Upload Form -->
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Valid ID Upload -->
        <div>
            <label for="valid_id">Upload Valid ID:</label>
            <input type="file" name="valid_id" id="valid_id">
            @error('valid_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Proof of Billing Upload -->
        <div>
            <label for="proof_of_billing">Upload Proof of Billing:</label>
            <input type="file" name="proof_of_billing" id="proof_of_billing">
            @error('proof_of_billing')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Upload Files</button>
    </form>

    <!-- Display Uploaded Files -->
    <h2>Uploaded Files</h2>
    @if($uploadedFiles->count() > 0)
        <ul>
            @foreach($uploadedFiles as $file)
                <li><a href="{{ $file->path }}">{{ $file->filename }}</a></li>
            @endforeach
        </ul>
    @else
        <p>No files uploaded yet.</p>
    @endif
</body>
</html>
