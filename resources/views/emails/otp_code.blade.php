<!doctype html>
<html lang="es">
  <body style="font-family:Arial, sans-serif; line-height:1.45; color:#0f172a;">
    <h2 style="margin:0 0 10px;">Instituto Coincidir</h2>

    <p>Tu código de verificación es:</p>

    <p style="font-size:28px; font-weight:800; letter-spacing:3px; margin:10px 0;">
      {{ $code }}
    </p>

    <p style="margin:0 0 10px;">
      Vence en aproximadamente <strong>{{ $expiresHuman }}</strong>.
    </p>

    <p style="color:#64748b; font-size:12px; margin-top:16px;">
      Si vos no pediste este código, podés ignorar este email.
    </p>
  </body>
</html>
