<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وثيقة موقعة</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            padding: 20px;
            color: #333;
        }
        .page {
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
        .page img {
            width: 100%;
            height: auto;
        }
        .signature-container {
            text-align: center;
            margin-top: 20px;
        }
        .signature-container img {
            max-width: 150px;
            border: 1px solid #000;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    @foreach($pages as $page)
        <div class="page">
            <img src="file://{{ $page }}" alt="Page {{ $loop->iteration }}">
        </div>
    @endforeach
    <div class="signature-container">
        <h2>التوقيع:</h2>
        @if($signature)
            <img src="{{ $signature }}" alt="Signature">
        @else
            <p>لا يوجد توقيع متاح.</p>
        @endif
    </div>
</body>
</html>
