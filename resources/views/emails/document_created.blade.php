<!DOCTYPE html>
<html>
<head>
    <title>Nouveau document à signer</title>
</head>
<body>
    <p>Bonjour,</p>
    <p>Un nouveau document intitulé <strong>{{ $document->title }}</strong> a été ajouté.</p>
    <p>Veuillez le signer en cliquant sur le lien suivant :</p>
    <p><a href="{{ route('document.sign', $document) }}">Signer le document</a></p>
    <p>Cordialement,</p>
</body>
</html>
