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
            margin: 0;
        }
        .page {
            position: relative;
            margin-bottom: 20px;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .page img.page-image {
            width: 100%;
            height: auto;
            display: block;
        }
        .signature-image {
            position: absolute;
            left: {{ $position_x }}%;
            top: {{ $position_y }}%;
            max-width: 200px;
            max-height: 100px;
            transform: scale({{ $scale ?? 1 }});
            transform-origin: top left;
        }
    </style>
</head>
<body>
    @foreach($pages as $index => $page)
        <div class="page">
            <img src="{{ $page }}" class="page-image" alt="Page {{ $loop->iteration }}">
            @if($index === 0)
                <img src="{{ $signature }}" class="signature-image" alt="Signature">
            @endif
        </div>
    @endforeach
</body>
</html>
