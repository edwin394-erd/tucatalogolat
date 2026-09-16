<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones | TuCatalogo.Lat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-yellow-50 to-indigo-100 text-gray-800">
    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:py-12">
        <div class="mb-6 flex items-center justify-between gap-4">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">&larr; Volver</a>
            <a href="{{ route('home') }}" aria-label="TuCatalogo.Lat"><img src="{{ asset('imgs/logo.png') }}" alt="TuCatalogo.Lat" class="h-10 w-auto"></a>
        </div>

        <article class="rounded-2xl bg-white p-5 shadow-xl ring-1 ring-gray-200 sm:p-8">
            <header class="border-b border-gray-200 pb-5">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600">Documento legal</p>
                <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">Términos y Condiciones</h1>
                <p class="mt-2 text-sm text-gray-500">Última actualización: {{ now()->format('d/m/Y') }}</p>
            </header>

            <div class="prose prose-gray mt-6 max-w-none text-sm leading-6">
                <p>Estos Términos y Condiciones regulan el uso de TuCatalogo.Lat, una plataforma que permite crear, administrar y publicar catálogos digitales. Al crear una cuenta o utilizar la plataforma, aceptas estas condiciones.</p>

                <h2>1. Partes y alcance</h2>
                <p>TuCatalogo.Lat es prestador de una herramienta tecnológica. La persona que crea un catálogo es el titular y responsable de su negocio, productos, precios, disponibilidad, entregas, cobros, promociones y comunicaciones con sus clientes.</p>

                <h2>2. Beneficios para titulares de catálogo</h2>
                <ul>
                    <li>Crear y compartir un catálogo digital con una dirección pública.</li>
                    <li>Administrar productos, categorías, imágenes, información de contacto y apariencia.</li>
                    <li>Recibir pedidos y organizar su seguimiento mediante estados como pendiente y completado.</li>
                    <li>Consultar métricas básicas de actividad, pedidos y visitas cuando estén disponibles.</li>
                    <li>Contar con controles para actualizar o eliminar el contenido propio.</li>
                </ul>

                <h2>3. Protecciones para titulares de catálogo</h2>
                <ul>
                    <li>El titular conserva la propiedad de sus textos, marcas, fotografías y demás contenido.</li>
                    <li>La cuenta debe protegerse con credenciales personales y no compartirse.</li>
                    <li>Los pedidos y datos asociados se muestran al titular del catálogo correspondiente, según los permisos de la plataforma.</li>
                    <li>TuCatalogo.Lat puede aplicar límites de almacenamiento, productos, imágenes y funciones según el plan contratado.</li>
                    <li>Se podrán corregir errores técnicos, suspender contenido ilícito o preservar información cuando exista una obligación legal o de seguridad.</li>
                </ul>

                <h2>4. Protecciones para clientes</h2>
                <ul>
                    <li>El cliente debe recibir información clara sobre productos, precios, disponibilidad y condiciones ofrecidas por el titular.</li>
                    <li>Los datos enviados en un pedido se utilizan para gestionarlo y comunicarlo al negocio correspondiente.</li>
                    <li>El cliente no debe proporcionar contraseñas, datos bancarios completos ni información innecesaria en las notas del pedido.</li>
                    <li>Las reclamaciones sobre productos, pagos, entregas, cambios o devoluciones deben dirigirse al titular del catálogo, salvo que la ley disponga otra cosa.</li>
                </ul>

                <h2>5. Cuenta y uso aceptable</h2>
                <p>Debes proporcionar información verdadera, mantenerla actualizada y tener capacidad legal para aceptar estos términos. Está prohibido usar la plataforma para fraude, suplantación, spam, malware, productos ilegales, contenido que infrinja derechos de terceros o actividades que pongan en riesgo a otros usuarios.</p>

                <h2>6. Contenido y propiedad intelectual</h2>
                <p>Solo debes subir contenido que poseas o tengas autorización para utilizar. Nos concedes una licencia limitada, no exclusiva y necesaria para alojar, procesar, mostrar y distribuir ese contenido dentro de las funciones de la plataforma. No adquirimos la propiedad de tus materiales.</p>

                <h2>7. Pedidos y comunicaciones</h2>
                <p>La creación de un pedido no garantiza por sí misma la aceptación, el pago ni la entrega. El titular debe confirmar y completar los pedidos bajo sus propias condiciones comerciales. TuCatalogo.Lat puede registrar el pedido para facilitar su gestión, pero no es parte de la compraventa entre cliente y titular.</p>

                <h2>8. Planes, límites y disponibilidad</h2>
                <p>Las características, límites, precios y duración de cada plan son los publicados en la plataforma y en los registros de la cuenta. Las funciones pueden depender del plan activo. Procuramos mantener el servicio disponible, pero pueden existir interrupciones por mantenimiento, fallos de terceros o causas fuera de nuestro control.</p>

                <h2>9. Privacidad y seguridad</h2>
                <p>Tratamos los datos necesarios para prestar el servicio, autenticar cuentas, gestionar catálogos y pedidos, prevenir abusos y cumplir obligaciones legales. Aplicamos medidas razonables de seguridad, pero ningún servicio conectado a Internet puede garantizar riesgo cero. Los titulares deben tratar los datos de sus clientes de forma lícita y confidencial.</p>

                <h2>10. Suspensión y cancelación</h2>
                <p>Puedes dejar de utilizar tu cuenta conforme a las funciones disponibles. Podemos limitar o suspender cuentas o contenido ante incumplimientos, fraude, riesgos de seguridad, requerimientos legales o falta de pago de un plan. Cuando sea razonable, informaremos el motivo y permitiremos resolver el incumplimiento.</p>

                <h2>11. Responsabilidad</h2>
                <p>TuCatalogo.Lat no garantiza ventas, visitas, disponibilidad de productos, exactitud del contenido publicado por terceros ni el resultado de una relación comercial. Cada titular responde por sus ofertas y obligaciones frente a sus clientes. Nada de estos términos limita derechos irrenunciables reconocidos por la legislación aplicable ni excluye responsabilidad que legalmente no pueda excluirse.</p>

                <h2>12. Cambios y contacto</h2>
                <p>Podemos actualizar estos términos para reflejar cambios legales, técnicos o del servicio. Publicaremos la versión vigente y, cuando el cambio sea material, procuraremos informar a los usuarios. El uso continuado después de la entrada en vigor implica aceptación de la versión actualizada.</p>
                <p>Para consultas, solicitudes o reportes relacionados con estos términos, utiliza los canales de contacto publicados en TuCatalogo.Lat.</p>
{{-- 
                <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-900">
                    Este documento es una base informativa para el funcionamiento de la plataforma. Antes de publicarlo como documento definitivo, debe revisarlo un profesional jurídico según el país, la actividad comercial y la normativa de protección de datos aplicable.
                </div> --}}
            </div>
        </article>
    </main>
</body>
</html>
