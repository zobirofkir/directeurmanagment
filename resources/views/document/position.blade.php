<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positionner la signature</title>
    <style>
        .signature-preview {
            position: absolute;
            cursor: move;
            max-width: 200px;
            max-height: 100px;
            border: 2px dashed #4CAF50;
            padding: 5px;
            z-index: 1000;
        }

        #document-container {
            position: relative;
            margin: 20px auto;
            max-width: 800px;
            background: #fff;
        }

        .document-frame {
            width: 100%;
            height: 800px;
            border: none;
        }

        .controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1001;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div id="document-container">
        <img src="{{ $signature }}" id="signature-preview" class="signature-preview">
        <iframe src="{{ Storage::url($document->file_path) }}" class="document-frame"></iframe>
    </div>

    <div class="controls">
        <form action="{{ route('document.download.signed', $document) }}" method="POST">
            @csrf
            <input type="hidden" name="signature" value="{{ $signature }}">
            <input type="hidden" name="language" value="{{ $language }}">
            <input type="hidden" name="position_x" id="position_x">
            <input type="hidden" name="position_y" id="position_y">
            <button type="submit">Confirmer la position</button>
        </form>
    </div>

    <script>
        const signaturePreview = document.getElementById('signature-preview');
        const container = document.getElementById('document-container');
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;

        signaturePreview.addEventListener('mousedown', dragStart);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);
        document.addEventListener('touchstart', dragStart);
        document.addEventListener('touchmove', drag);
        document.addEventListener('touchend', dragEnd);

        function dragStart(e) {
            if (e.type === 'touchstart') {
                initialX = e.touches[0].clientX - signaturePreview.offsetLeft;
                initialY = e.touches[0].clientY - signaturePreview.offsetTop;
            } else {
                initialX = e.clientX - signaturePreview.offsetLeft;
                initialY = e.clientY - signaturePreview.offsetTop;
            }
            isDragging = true;
        }

        function drag(e) {
            if (isDragging) {
                e.preventDefault();

                if (e.type === 'touchmove') {
                    currentX = e.touches[0].clientX - initialX;
                    currentY = e.touches[0].clientY - initialY;
                } else {
                    currentX = e.clientX - initialX;
                    currentY = e.clientY - initialY;
                }

                // Get container boundaries
                const bounds = container.getBoundingClientRect();

                // Constrain movement within container
                currentX = Math.max(bounds.left, Math.min(bounds.right - signaturePreview.offsetWidth, currentX));
                currentY = Math.max(bounds.top, Math.min(bounds.bottom - signaturePreview.offsetHeight, currentY));

                // Update position
                signaturePreview.style.left = currentX + 'px';
                signaturePreview.style.top = currentY + 'px';

                // Store relative positions
                const relativeX = currentX - bounds.left;
                const relativeY = currentY - bounds.top;

                document.getElementById('position_x').value = relativeX;
                document.getElementById('position_y').value = relativeY;
            }
        }

        function dragEnd() {
            isDragging = false;
        }
    </script>
</body>
</html>
