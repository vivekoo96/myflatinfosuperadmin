<!DOCTYPE html>
<html>
<head>
    <title>Amazon Textract OCR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Upload File for OCR (Amazon Textract)</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ implode(', ', $errors->all()) }}
        </div>
    @endif

    <form action="{{ route('textract.process') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Process File</button>
    </form>

    @isset($lines)
        <h4>Extracted Text:</h4>
        <div class="p-3 bg-white border rounded">
            @foreach ($lines as $line)
                <p>{{ $line }}</p>
            @endforeach
        </div>
    @endisset
</div>

</body>
</html>
