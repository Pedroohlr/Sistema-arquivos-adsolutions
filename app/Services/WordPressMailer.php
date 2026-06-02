<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WordPressMailer
{
    private string $url;
    private string $apiKey;
    private string $fromName;
    private string $fromEmail;

    public function __construct()
    {
        $this->url       = config('wpmailer.url');
        $this->apiKey    = config('wpmailer.api_key');
        $this->fromName  = config('wpmailer.from_name');
        $this->fromEmail = config('wpmailer.from_email');
    }

    // ── API pública ───────────────────────────────────────────────────────────

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $response = Http::timeout(10)
                ->withHeader('X-Api-Key', $this->apiKey)
                ->post($this->url, [
                    'to'         => $to,
                    'subject'    => $subject,
                    'body'       => $body,
                    'from_name'  => $this->fromName,
                    'from_email' => $this->fromEmail,
                ]);

            if (! $response->successful() || ! ($response->json('success') ?? false)) {
                Log::error('WordPressMailer: falha no envio', [
                    'to'      => $to,
                    'subject' => $subject,
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('WordPressMailer: exceção', ['message' => $e->getMessage()]);
            return false;
        }
    }

    // ── Templates prontos ────────────────────────────────────────────────────

    public function enviarCredenciais(string $to, string $usuario, string $senha, string $grupoNome): bool
    {
        $subject = 'Suas credenciais de acesso – ' . $grupoNome;
        $body    = $this->template('Suas credenciais de acesso', [
            'Olá! Suas credenciais para acessar o portal da <strong>ADSolutions</strong> foram criadas.',
            'Use os dados abaixo para fazer login:',
        ], [
            ['label' => 'Usuário',  'value' => $usuario],
            ['label' => 'Senha',    'value' => $senha],
            ['label' => 'Grupo',    'value' => $grupoNome],
        ], [
            'label' => 'Acessar portal',
            'url'   => rtrim(config('app.url'), '/') . '/cliente/login',
        ]);

        return $this->send($to, $subject, $body);
    }

    public function enviarNovoArquivo(string $to, string $nomeArquivo, string $grupoNome): bool
    {
        $subject = 'Novo arquivo disponível – ' . $grupoNome;
        $body    = $this->template('Novo arquivo disponível', [
            'Um novo arquivo foi adicionado ao seu portal de acesso.',
        ], [
            ['label' => 'Arquivo', 'value' => $nomeArquivo],
            ['label' => 'Grupo',   'value' => $grupoNome],
        ], [
            'label' => 'Ver arquivos',
            'url'   => rtrim(config('app.url'), '/') . '/cliente/dashboard',
        ]);

        return $this->send($to, $subject, $body);
    }

    public function enviarSenhaAlterada(string $to, string $usuario): bool
    {
        $subject = 'Senha alterada com sucesso';
        $body    = $this->template('Senha alterada', [
            'Sua senha de acesso ao portal da <strong>ADSolutions</strong> foi alterada com sucesso.',
            'Se você não realizou essa alteração, entre em contato conosco imediatamente.',
        ], [
            ['label' => 'Usuário', 'value' => $usuario],
        ]);

        return $this->send($to, $subject, $body);
    }

    // ── Base HTML ────────────────────────────────────────────────────────────

    private function template(string $title, array $paragraphs, array $fields = [], ?array $cta = null): string
    {
        $ps = implode('', array_map(
            fn ($p) => "<p style='margin:0 0 12px;color:#374151;font-size:15px;line-height:1.6'>$p</p>",
            $paragraphs
        ));

        $rows = '';
        foreach ($fields as $field) {
            $rows .= "
            <tr>
                <td style='padding:10px 16px;border-bottom:1px solid #e5e7eb;color:#6b7280;font-size:13px;width:120px;white-space:nowrap'>
                    {$field['label']}
                </td>
                <td style='padding:10px 16px;border-bottom:1px solid #e5e7eb;color:#111827;font-size:14px;font-weight:600'>
                    {$field['value']}
                </td>
            </tr>";
        }

        $table = $rows ? "
        <table style='width:100%;border-collapse:collapse;margin:20px 0;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden'>
            $rows
        </table>" : '';

        $button = $cta ? "
        <div style='text-align:center;margin:28px 0 8px'>
            <a href='{$cta['url']}' style='display:inline-block;background:#f2c700;color:#111;font-weight:700;font-size:15px;padding:12px 32px;border-radius:6px;text-decoration:none'>
                {$cta['label']}
            </a>
        </div>" : '';

        return "<!DOCTYPE html>
<html lang='pt-BR'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'></head>
<body style='margin:0;padding:0;background:#f3f4f6;font-family:sans-serif'>
    <table width='100%' cellpadding='0' cellspacing='0' style='background:#f3f4f6;padding:32px 16px'>
        <tr><td align='center'>
            <table width='100%' style='max-width:560px;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.08)'>

                <!-- Header -->
                <tr>
                    <td style='background:#111827;padding:24px 32px;text-align:center'>
                        <span style='color:#f2c700;font-size:22px;font-weight:800;letter-spacing:.5px'>ADSolutions</span>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style='padding:32px'>
                        <h1 style='margin:0 0 20px;color:#111827;font-size:20px;font-weight:700'>$title</h1>
                        $ps
                        $table
                        $button
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style='background:#f9fafb;border-top:1px solid #e5e7eb;padding:16px 32px;text-align:center'>
                        <p style='margin:0;color:#9ca3af;font-size:12px'>Este é um e-mail automático. Não responda a esta mensagem.</p>
                    </td>
                </tr>

            </table>
        </td></tr>
    </table>
</body>
</html>";
    }
}
