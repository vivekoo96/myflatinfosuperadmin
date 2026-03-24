<!DOCTYPE html>
<html>
<head>
    <title>Upload Answer Sheet (Google Vision)</title>
</head>
<body>
    <h2>Upload Answer Sheet</h2>

    <form action="{{ route('vision.ocr.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="sheet" accept=".jpg,.jpeg,.png,.pdf" required>
        <button type="submit">Upload & Extract Text</button>
    </form>
</body>
</html>
