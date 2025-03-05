@component('mail::message')
# Nouveau Document

Un nouveau document a été créé par {{ $userName }}.

**Détails du document:**
- Titre: {{ $document->title }}
- Catégorie: {{ $document->category }}
- Créé par: {{ $userName }} ({{ $userRole }})

@component('mail::button', ['url' => $url])
Voir le Document
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
