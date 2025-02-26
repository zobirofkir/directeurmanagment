<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Signé</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }
        body {
            font-family: 'Amiri', sans-serif; /* Utilisation de la police */
            direction: ltr; /* Direction du texte de gauche à droite */
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
    <h1>Document Signé : {{ $document->title }}</h1>
    <div class="document-container">
        <iframe src="{{ Storage::disk('local')->url($document->file_path) }}" width="100%" height="500px"></iframe>
    </div>
    <div class="signature-container">
        <h2>Signature :</h2>
        <img src="{{ $signature }}" alt="Signature">
    </div>
</body>
</html>
