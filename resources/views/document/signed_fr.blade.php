<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Signé</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            position: relative;
            width: 210mm;
            height: 297mm;
            page-break-after: always;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .page img.page-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            object-fit: fill;
        }
        .signature-image {
            position: absolute;
            /* Convert percentage to pixels (1mm = 3.78px approximately) */
            left: {{ $position_x * 2.1 }}mm;
            top: {{ $position_y * 2.97 }}mm;
            width: 40mm;
            height: auto;
            transform-origin: top left;
            transform: scale({{ $scale ?? 1 }});
            z-index: 1000;
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
