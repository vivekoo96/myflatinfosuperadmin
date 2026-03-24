<!DOCTYPE html>
<html>
<head>
    <title>Upload Answer Sheet</title>
</head>
<body>
    <h2>Upload Answer Sheet for OCR</h2>

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('ocr.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="sheet" accept=".jpg,.jpeg,.png,.pdf" required>
        <button type="submit">Upload & Process</button>
    </form>
</body>
</html>
