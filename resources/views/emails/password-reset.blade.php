<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Restablece tu contraseña</title>
</head>
<body style="margin:0; padding:0; background-color:#eef2ff; color:#172033; font-family:Arial, Helvetica, sans-serif;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">Crea una nueva contraseña para volver a entrar a tu cuenta de TuCatálogo.lat.</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#eef2ff; padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px; background-color:#ffffff; border:1px solid #dbe3f0; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(30,41,59,0.10);">
                    <tr>
                        <td style="background-color:#e7e7f0; padding:28px 32px;">
                            <img src="{{ asset('imgs/logo.png') }}" alt="TuCatálogo.lat" width="260" style="display:block; width:260px; max-width:100%; height:auto; border:0;">
                            <div style="margin-top:9px; font-size:12px; letter-spacing:1.5px; text-transform:uppercase; color:#4338ca;">Tu catálogo, siempre contigo</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 32px 32px;">
                            <div style="display:inline-block; padding:8px 12px; border-radius:999px; background-color:#eef2ff; color:#4338ca; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase;">Seguridad de tu cuenta</div>
                            <h1 style="margin:20px 0 12px; color:#111827; font-size:28px; line-height:1.2;">Restablece tu contraseña</h1>
                            <p style="margin:0 0 18px; color:#4b5563; font-size:16px; line-height:1.65;">Hola{{ $user->name ? ' ' . $user->name : '' }},</p>
                            <p style="margin:0 0 26px; color:#4b5563; font-size:16px; line-height:1.65;">Recibimos una solicitud para cambiar la contraseña de tu cuenta. Pulsa el botón para crear una nueva contraseña de forma segura.</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 28px;">
                                <tr>
                                    <td align="center" style="border-radius:8px; background-color:#4f46e5;">
                                        <a href="{{ $resetUrl }}" style="display:inline-block; padding:15px 24px; border:1px solid #4f46e5; border-radius:8px; color:#ffffff; font-size:15px; font-weight:700; text-decoration:none;">Crear nueva contraseña</a>
                                    </td>
                                </tr>
                            </table>
                            <div style="border-left:4px solid #facc15; background-color:#fffbeb; padding:14px 16px; color:#4b5563; font-size:14px; line-height:1.55;">Este enlace estará disponible durante <strong>{{ $expireMinutes }} minutos</strong> y solo puede utilizarse una vez.</div>
                            <p style="margin:26px 0 0; color:#6b7280; font-size:13px; line-height:1.6;">Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña actual seguirá siendo válida.</p>
                            <p style="margin:22px 0 0; color:#4b5563; font-size:15px; line-height:1.6;">Saludos,<br><strong>El equipo de TuCatálogo.lat</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #e5e7eb; padding:20px 32px; background-color:#f8fafc;">
                            <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.5;">Este es un mensaje automático. Por favor, no respondas directamente a este correo.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:18px 0 0; color:#94a3b8; font-size:11px;">© {{ date('Y') }} TuCatálogo.lat</p>
            </td>
        </tr>
    </table>
</body>
</html>
