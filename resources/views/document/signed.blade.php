<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document signé</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
        }
        .document-container {
            margin-bottom: 20px;
        }
        .signature-container {
            text-align: center;
            margin-top: 20px;
        }
        .signature-container img {
            border: 1px solid #000;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <h1>Document signé : {{ $document->title }}</h1>
    <div class="document-container">
        @if(Storage::exists($document->file_path))
            <iframe src="{{ Storage::url($document->file_path) }}" width="100%" height="500px"></iframe>
        @else
            <p>Le document n'est pas disponible.</p>
        @endif
    </div>
    <div class="signature-container">
        <h2>Signature :</h2>
        @if($signature && Storage::exists($signature))
            <img src="{{ Storage::url($signature) }}" alt="Signature">
        @else
            <p>La signature n'est pas disponible.</p>
        @endif
    </div>
</body>
</html>
