<!doctype html>
<html lang="pt-BR">
  <body style="font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif; color:#0f172a;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
      <h2 style="margin:0 0 4px 0;">Sinesys Authenticator</h2>
      <p style="margin:0 0 16px 0;color:#475569;">Redefinição de senha</p>
      <p>Olá {{ $user->name ?? 'usuário' }},</p>
      <p>Recebemos um pedido para redefinir a senha da sua conta.</p>
      <p style="margin:24px 0;">
        <a href="{{ $url }}" style="display:inline-block;background:#0ea5e9;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:6px;">Redefinir senha</a>
      </p>
      <p>Este link expira em 60 minutos.</p>
      <p>Se você não solicitou esta ação, ignore este e‑mail.</p>
      <p style="margin-top:24px;">Atenciosamente,<br>2FAuth</p>
      <hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0;">
      <p style="font-size:12px;color:#64748b;">Se tiver problemas ao clicar no botão, copie e cole a URL abaixo no navegador:</p>
      <p style="font-size:12px;color:#64748b;word-break:break-all;">{{ $url }}</p>
      <p style="font-size:12px;color:#94a3b8;margin-top:16px;">© 2025 Sinesys Authenticator. Powered by 2FAuth.</p>
    </div>
  </body>
  </html>


