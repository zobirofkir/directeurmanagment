<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature du document</title>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            direction: rtl;
            padding: 20px;
            color: #333;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
            animation: slideDown 1s ease-in-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        iframe {
            display: block;
            margin: 0 auto 20px;
            border: none;
            max-width: 100%;
            height: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-in-out;
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        #signature-pad-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        #signature-pad {
            border: 2px solid #000;
            width: 100%;
            height: 250px;
            border-radius: 8px;
            background-color: #fff;
            touch-action: none;
            transition: box-shadow 0.3s ease;
        }

        #signature-pad:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .clear-button {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .clear-button:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .language-select {
            margin-bottom: 20px;
        }

        .language-select label {
            font-weight: bold;
            margin-right: 10px;
        }

        .language-select select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .language-select select:hover {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }

        @media (max-width: 600px) {
            iframe {
                height: 400px;
            }

            #signature-pad {
                height: 150px;
            }

            .form-container {
                padding: 15px;
                margin: 10px;
            }

            button[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Signature du document : {{ $document->title }}</h1>
    <iframe src="{{ Storage::url($document->file_path) }}" width="100%" height="500px"></iframe>
    <form action="{{ route('document.select.position', $document) }}" method="POST">
        @csrf
        <div class="form-container">
            <div class="language-select">
                <label for="language">Langue du document :</label>
                <select id="language" name="language">
                    <option value="fr">Français</option>
                    <option value="en">English</option>
                    <option value="ar">العربية</option>
                </select>
            </div>
            <div id="signature-pad-container">
                <canvas id="signature-pad"></canvas>
                <button type="button" id="clear-button" class="clear-button">Effacer la signature</button>
            </div>
            <input type="hidden" id="signature" name="signature">
            <button type="submit">Télécharger le document signé</button>
        </div>
    </form>

    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16,
            velocityFilterWeight: 0.7,
        });

        document.getElementById('clear-button').addEventListener('click', () => {
            signaturePad.clear();
        });

        document.querySelector('form').addEventListener('submit', (event) => {
            const signatureInput = document.getElementById('signature');
            if (signaturePad.isEmpty()) {
                alert('Veuillez ajouter une signature d\'abord.');
                event.preventDefault();
            } else {
                signatureInput.value = signaturePad.toDataURL();
            }
        });
    </script>
</body>
</html>
