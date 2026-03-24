<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - MyFlatInfo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: #000;
      display: flex;
      align-items: flex-start;
      justify-content: center;
    }
    .container {
      max-width: 800px;
      width: 90%;
      background: #fff;
      padding: 30px;
      margin-top: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.18);
    }
    h1 {
      text-align: center;
      font-weight: 700;
      font-size: 2em;
      margin-bottom: 25px;
      color: #000;
      letter-spacing: 1px;
    }
    .about-content {
      color: #222;
      font-size: 1.08em;
      line-height: 1.7;
    }
  </style>
</head>
<body>
  <div class="container">
    @php
      $setting = \App\Models\Setting::first();
    @endphp
    @if($setting && $setting->logo)
      <div style="text-align:center; margin-bottom:20px;">
        <img src="{{ asset($setting->logo) }}" alt="Logo" style="max-width:160px; max-height:90px;">
      </div>
    @endif
    <h1>About MyFlatInfo</h1>
    <div class="about-content">
      {!! $aboutUs !!}
    </div>
  </div>
</body>
</html>
