<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توقيع المستند</title>
    <!-- تضمين مكتبة Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        #signature-pad {
            border: 1px solid #000;
            width: 100%;
            height: 200px;
        }
        .clear-button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>توقيع المستند: {{ $document->title }}</h1>
    <iframe src="{{ Storage::url($document->file_path) }}" width="100%" height="500px"></iframe>
    <form action="{{ route('document.download.signed', $document) }}" method="POST">
        @csrf
        <!-- منطقة التوقيع -->
        <div id="signature-pad-container">
            <canvas id="signature-pad"></canvas>
            <button type="button" id="clear-button" class="clear-button">مسح التوقيع</button>
        </div>
        <!-- حقل مخفي لتخزين التوقيع كصورة -->
        <input type="hidden" id="signature" name="signature">
        <button type="submit">تنزيل المستند الموقع</button>
    </form>

    <script>
        // تهيئة Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)', // خلفية بيضاء
            penColor: 'rgb(0, 0, 0)' // لون القلم أسود
        });

        // زر مسح التوقيع
        document.getElementById('clear-button').addEventListener('click', () => {
            signaturePad.clear();
        });

        document.querySelector('form').addEventListener('submit', (event) => {
            const signatureInput = document.getElementById('signature');
            if (signaturePad.isEmpty()) {
                alert('الرجاء إضافة التوقيع أولاً.');
                event.preventDefault(); // إيقاف الإرسال إذا لم يتم التوقيع
            } else {
                // تحويل التوقيع إلى صورة بتنسيق base64
                signatureInput.value = signaturePad.toDataURL();
            }
        });

</script>
</body>
</html>
