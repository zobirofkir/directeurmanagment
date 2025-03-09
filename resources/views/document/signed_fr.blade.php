<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Signé</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            color: #333;
            position: relative;
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
        .signature-image {
            position: absolute;
            left: {{ $position_x }}px;
            top: {{ $position_y }}px;
            max-width: 200px;
            max-height: 100px;
        }
        .page-image {
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @foreach($pages as $index => $page)
        <div class="page" style="position: relative; margin-bottom: 20px;">
            <img src="{{ $page }}" class="page-image">
            @if($index === 0)
                <img src="{{ $signature }}" class="signature-image">
            @endif
        </div>
    @endforeach

</body>
</html>
