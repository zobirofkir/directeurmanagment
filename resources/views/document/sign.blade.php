<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signer le Document</title>
    <style>
        /* General body styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }

        /* Container for the signature form */
        .container {
            width: 80%;
            max-width: 800px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        /* Alert box styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Canvas style */
        #signature-pad {
            border: 2px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            margin-bottom: 20px;
        }

        /* Button styles */
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            h1 {
                font-size: 1.5rem;
            }
            .container {
                width: 95%;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Signer le Document : {{ $document->title }}</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <canvas id="signature-pad" width="400" height="200"></canvas>
        <br>
        <button id="clear">Effacer</button>
        <button id="save">Enregistrer la Signature</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        document.getElementById('clear').addEventListener('click', () => {
            signaturePad.clear();
        });

        document.getElementById('save').addEventListener('click', async () => {
            if (signaturePad.isEmpty()) {
                alert('Veuillez fournir votre signature.');
                return;
            }

            const signature = signaturePad.toDataURL("image/png");

            try {
                const response = await fetch("{{ route('document.download.signed', $document) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ signature })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Échec du téléchargement du document signé.');
                }

                const blob = await response.blob();
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "document_signe.pdf";
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            } catch (error) {
                console.error("Erreur :", error);
                alert(error.message);
            }
        });
    </script>
</body>
</html>
