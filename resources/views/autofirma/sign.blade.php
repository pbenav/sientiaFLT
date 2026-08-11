<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmar Factura con AutoFirma</title>
    <!-- TailwindCSS for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">
    
    <div class="bg-white p-8 rounded-lg shadow-md max-w-lg w-full text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Firma Digital (AutoFirma)</h2>
        <p class="text-gray-600 mb-6">Vas a firmar la factura <strong>{{ $invoice->invoice_number }}</strong> usando tu certificado digital local.</p>
        
        <div class="mb-8 p-4 bg-blue-50 text-blue-800 rounded text-sm text-left">
            <ul class="list-disc pl-5 space-y-1">
                <li>Asegúrate de tener la aplicación <strong>AutoFirma</strong> iniciada o instalada.</li>
                <li>Se generará una firma <strong>XAdES Enveloped</strong> según el estándar FacturaE.</li>
            </ul>
        </div>

        <button id="btnSign" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-200 shadow">
            Iniciar AutoFirma y Firmar
        </button>
        
        <div class="mt-4">
            <a href="/admin/invoices" class="text-sm text-gray-500 hover:text-gray-700 underline">Cancelar y volver</a>
        </div>
    </div>

    <!-- Inject AutoScript -->
    <script src="{{ asset('js/autoscript.js') }}"></script>
    <script>
        document.getElementById('btnSign').addEventListener('click', function() {
            
            Swal.fire({
                title: 'Abriendo AutoFirma...',
                html: 'Por favor, acepta el diálogo del navegador y selecciona tu certificado.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const base64Data = "{{ $base64Document }}";
            
            // AutoFirma params for FacturaE / XAdES Enveloped
            // See AutoFirma documentation for full parameters format
            // Here we use format: XAdES (enveloped)
            MiniApplet.sign(
                base64Data,
                "XML", // Algorithm or data type
                "XAdES", // Format
                "format=XAdES Enveloped", // Extra params
                function (signedBase64) {
                    // Success callback
                    Swal.fire({
                        title: '¡Firma Completada!',
                        text: 'Enviando la factura firmada al servidor...',
                        icon: 'success',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    // Enviar al servidor
                    fetch("{{ route('autofirma.save', ['invoice' => $invoice->id]) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            signature_base64: signedBase64
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            window.location.href = data.redirect;
                        } else {
                            Swal.fire('Error', 'Hubo un error al guardar la factura firmada.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Error de red al guardar.', 'error');
                    });
                },
                function (errorType, errorMessage) {
                    // Error callback
                    console.error("AutoFirma error:", errorType, errorMessage);
                    Swal.fire(
                        'Firma Cancelada / Error',
                        errorMessage || 'No se pudo completar la firma.',
                        'error'
                    );
                }
            );
        });
    </script>
</body>
</html>
