<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positionner la signature</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1002;
        }

        .signature-preview {
            position: absolute;
            cursor: move;
            max-width: 200px; /* Limit initial signature size */
            height: auto;
            border: 2px dashed #4CAF50;
            padding: 0;
            z-index: 1000;
            user-select: none;
            transform-origin: center center;
            background-color: rgba(255, 255, 255, 0.8);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .signature-preview:hover {
            transform: scale(1.02);
        }

        .signature-preview.dragging {
            opacity: 0.8;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-color: #2196F3;
        }

        .signature-preview.invalid-position {
            border-color: #ff4444;
        }

        #document-container {
            position: relative;
            flex-grow: 1;
            height: calc(100vh - 140px);
            overflow: auto; /* Changed from hidden to auto */
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .document-frame {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1001;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 90%;
        }

        .toolbar {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            z-index: 1001;
        }

        .toolbar-btn {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .toolbar-btn:hover {
            background: #f5f5f5;
        }

        .toolbar-btn.active {
            background: #e3f2fd;
            border-color: #2196F3;
        }

        /* Add responsive styles */
        @media (max-width: 768px) {
            .controls {
                padding: 10px;
                bottom: 10px;
            }

            .toolbar {
                top: 10px;
                left: 10px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 14px;
            }

            .zoom-controls {
                right: 10px;
            }
        }

        /* Add page guidelines */
        .guideline {
            position: absolute;
            background: rgba(33, 150, 243, 0.3);
            pointer-events: none;
            display: none;
        }

        .guideline.visible {
            display: block;
        }

        .guideline-h {
            width: 100%;
            height: 1px;
        }

        .guideline-v {
            width: 1px;
            height: 100%;
        }

        /* Add tooltip */
        .tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            pointer-events: none;
            z-index: 1003;
            display: none;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .zoom-controls {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .zoom-btn {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .zoom-btn:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="toolbar-btn" onclick="toggleRotation()" title="Rotation libre">
            <i class="fas fa-sync"></i>
        </button>
        <button class="toolbar-btn" onclick="toggleGuidelines()" title="Guides d'alignement">
            <i class="fas fa-ruler-combined"></i>
        </button>
        <button class="toolbar-btn" onclick="toggleSnap()" title="Magnétisme">
            <i class="fas fa-magnet"></i>
        </button>
    </div>

    <div id="document-container">
        <div class="guideline guideline-h" id="guideline-h"></div>
        <div class="guideline guideline-v" id="guideline-v"></div>
        <div class="tooltip" id="position-tooltip"></div>
        <img src="{{ $signature }}" id="signature-preview" class="signature-preview">
        <iframe src="{{ Storage::url($document->file_path) }}" id="document-frame" class="document-frame"></iframe>
    </div>

    <div class="zoom-controls">
        <button class="zoom-btn" onclick="adjustZoom(0.1)">+</button>
        <button class="zoom-btn" onclick="adjustZoom(-0.1)">-</button>
        <button class="zoom-btn" onclick="resetZoom()">↺</button>
    </div>

    <div class="controls">
        <form action="{{ route('document.download.signed', $document) }}" method="POST" id="signature-form">
            @csrf
            <input type="hidden" name="signature" value="{{ $signature }}">
            <input type="hidden" name="language" value="{{ $language }}">
            <input type="hidden" name="position_x" id="position_x">
            <input type="hidden" name="position_y" id="position_y">
            <input type="hidden" name="scale" id="scale_factor" value="1">
            <button type="submit" class="btn">
                <i class="fas fa-check"></i>
                Confirmer la position
            </button>
        </form>
        <button onclick="resetPosition()" class="btn btn-secondary">
            <i class="fas fa-undo"></i>
            Réinitialiser
        </button>
    </div>

    <script>
        const signaturePreview = document.getElementById('signature-preview');
        const container = document.getElementById('document-container');
        const documentFrame = document.getElementById('document-frame');
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let scale = 1;
        let xOffset = 0;
        let yOffset = 0;
        let rotation = 0;
        let enableRotation = false;
        let showGuidelines = false;
        let enableSnap = false;

        // Initialize signature position
        window.addEventListener('load', () => {
            const bounds = container.getBoundingClientRect();
            const initialX = bounds.width / 2 - signaturePreview.offsetWidth / 2;
            const initialY = bounds.height / 2 - signaturePreview.offsetHeight / 2;
            setPosition(initialX, initialY);
            updatePositionInputs();
        });

        function initDraggable(element) {
            element.addEventListener('mousedown', dragStart);
            element.addEventListener('touchstart', dragStart, { passive: false });
        }

        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);
        document.addEventListener('touchmove', drag, { passive: false });
        document.addEventListener('touchend', dragEnd);

        initDraggable(signaturePreview);

        function dragStart(e) {
            if (e.type === 'touchstart') {
                initialX = e.touches[0].clientX - xOffset;
                initialY = e.touches[0].clientY - yOffset;
            } else {
                initialX = e.clientX - xOffset;
                initialY = e.clientY - yOffset;
            }
            isDragging = true;
            signaturePreview.classList.add('dragging');
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();

            let currentX, currentY;
            if (e.type === 'touchmove') {
                currentX = e.touches[0].clientX - initialX;
                currentY = e.touches[0].clientY - initialY;
            } else {
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
            }

            const bounds = container.getBoundingClientRect();
            const frameRect = documentFrame.getBoundingClientRect();

            // Add snapping logic
            if (enableSnap) {
                const snapThreshold = 10;
                const centerX = frameRect.left + frameRect.width / 2;
                const centerY = frameRect.top + frameRect.height / 2;

                if (Math.abs(currentX + signaturePreview.offsetWidth / 2 - centerX) < snapThreshold) {
                    currentX = centerX - signaturePreview.offsetWidth / 2;
                }
                if (Math.abs(currentY + signaturePreview.offsetHeight / 2 - centerY) < snapThreshold) {
                    currentY = centerY - signaturePreview.offsetHeight / 2;
                }
            }

            // Update guidelines
            if (showGuidelines) {
                const guidelineH = document.getElementById('guideline-h');
                const guidelineV = document.getElementById('guideline-v');

                guidelineH.style.top = (currentY + signaturePreview.offsetHeight / 2) + 'px';
                guidelineV.style.left = (currentX + signaturePreview.offsetWidth / 2) + 'px';

                guidelineH.classList.add('visible');
                guidelineV.classList.add('visible');
            }

            // Update position tooltip
            const tooltip = document.getElementById('position-tooltip');
            tooltip.style.display = 'block';
            tooltip.style.left = (currentX + signaturePreview.offsetWidth + 10) + 'px';
            tooltip.style.top = currentY + 'px';
            tooltip.textContent = `X: ${Math.round((currentX - frameRect.left) / frameRect.width * 100)}% Y: ${Math.round((currentY - frameRect.top) / frameRect.height * 100)}%`;

            setPosition(currentX, currentY);
            updatePositionInputs();
        }

        function dragEnd() {
            isDragging = false;
            signaturePreview.classList.remove('dragging');
        }

        function setPosition(x, y) {
            xOffset = x;
            yOffset = y;
            signaturePreview.style.left = x + 'px';
            signaturePreview.style.top = y + 'px';
        }

        function updatePositionInputs() {
            const bounds = container.getBoundingClientRect();
            const frameRect = documentFrame.getBoundingClientRect();

            // Calculate position relative to the document frame
            const relativeX = (xOffset - frameRect.left) / frameRect.width * 100;
            const relativeY = (yOffset - frameRect.top) / frameRect.height * 100;

            document.getElementById('position_x').value = relativeX.toFixed(2);
            document.getElementById('position_y').value = relativeY.toFixed(2);
            document.getElementById('scale_factor').value = scale.toFixed(2);
        }

        function resetPosition() {
            const bounds = container.getBoundingClientRect();
            setPosition(
                bounds.width / 2 - signaturePreview.offsetWidth / 2,
                bounds.height / 2 - signaturePreview.offsetHeight / 2
            );
            updatePositionInputs();
            resetZoom();
        }

        function adjustZoom(delta) {
            const oldScale = scale;
            scale = Math.max(0.5, Math.min(2, scale + delta));

            // Get the center point of the signature
            const centerX = xOffset + (signaturePreview.offsetWidth / 2);
            const centerY = yOffset + (signaturePreview.offsetHeight / 2);

            // Calculate new position while maintaining the center point
            const newWidth = signaturePreview.offsetWidth * scale;
            const newHeight = signaturePreview.offsetHeight * scale;
            const newX = centerX - (newWidth / 2);
            const newY = centerY - (newHeight / 2);

            setPosition(newX, newY);
            signaturePreview.style.transform = `scale(${scale})`;
            updatePositionInputs();
        }

        function resetZoom() {
            scale = 1;
            signaturePreview.style.transform = `scale(${scale})`;
            updatePositionInputs();
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            const bounds = container.getBoundingClientRect();
            if (parseFloat(signaturePreview.style.left) > bounds.width) {
                setPosition(bounds.width - signaturePreview.offsetWidth, parseFloat(signaturePreview.style.top));
            }
            if (parseFloat(signaturePreview.style.top) > bounds.height) {
                setPosition(parseFloat(signaturePreview.style.left), bounds.height - signaturePreview.offsetHeight);
            }
            updatePositionInputs();
        });

        // Add form submission handler
        document.getElementById('signature-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Ensure positions are updated one final time before submission
            updatePositionInputs();

            // Submit the form
            this.submit();
        });

        // Add touch event handlers
        document.addEventListener('touchmove', function(e) {
            if (isDragging) {
                e.preventDefault(); // Prevent scrolling while dragging
            }
        }, { passive: false });

        // Add rotation handling
        document.addEventListener('keydown', function(e) {
            if (enableRotation && e.key === 'r') {
                rotation = (rotation + 90) % 360;
                signaturePreview.style.transform = `scale(${scale}) rotate(${rotation}deg)`;
            }
        });

        // Modify the cleanup function
        function cleanup() {
            document.removeEventListener('mousemove', drag);
            document.removeEventListener('mouseup', dragEnd);
            document.removeEventListener('touchmove', drag);
            document.removeEventListener('touchend', dragEnd);
            const guidelineH = document.getElementById('guideline-h');
            const guidelineV = document.getElementById('guideline-v');
            if (guidelineH) guidelineH.classList.remove('visible');
            if (guidelineV) guidelineV.classList.remove('visible');
        }

        // Add cleanup on page unload
        window.addEventListener('unload', cleanup);

        // Add these new functions
        function toggleRotation() {
            enableRotation = !enableRotation;
            document.querySelector('.toolbar-btn:nth-child(1)').classList.toggle('active');
        }

        function toggleGuidelines() {
            showGuidelines = !showGuidelines;
            document.querySelector('.toolbar-btn:nth-child(2)').classList.toggle('active');
        }

        function toggleSnap() {
            enableSnap = !enableSnap;
            document.querySelector('.toolbar-btn:nth-child(3)').classList.toggle('active');
        }
    </script>
</body>
</html>
