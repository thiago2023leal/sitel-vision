<?php
/**
 * Handler do formulário de diagnóstico (diagnostico.html).
 * Recebe POST, valida, envia e-mail via API do Resend (HTTPS), não mail()/SMTP local —
 * a hospedagem não entrega e-mail de forma confiável (roteamento local do domínio e
 * bloqueio/limite de envio direto), então a entrega sai inteira pela infraestrutura do Resend.
 */

require __DIR__ . '/resend-config.php';

header('Content-Type: application/json; charset=utf-8');

function respond($ok, $message) {
    http_response_code($ok ? 200 : 400);
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Método não permitido.');
}

// Honeypot: se o campo invisível veio preenchido, é bot — responde "ok" sem enviar nada.
if (!empty($_POST['website'])) {
    respond(true, 'Recebido.');
}

function clean_line($value) {
    // Remove quebras de linha para impedir injeção de cabeçalho de e-mail.
    $value = str_replace(["\r", "\n"], ' ', (string) $value);
    return trim($value);
}

$perfil = clean_line($_POST['perfil'] ?? 'Não informado');
$nome = clean_line($_POST['nome'] ?? '');
$telefone = clean_line($_POST['telefone'] ?? '');
$email = clean_line($_POST['email'] ?? '');
$mensagem = trim(str_replace("\r\n", "\n", (string) ($_POST['mensagem'] ?? '')));

$servicos = $_POST['servicos'] ?? [];
if (!is_array($servicos)) {
    $servicos = [$servicos];
}
$servicos = array_map('clean_line', $servicos);
$servicosTexto = count($servicos) ? implode(', ', $servicos) : 'Não especificado';

if ($nome === '' || $telefone === '' || $email === '' || $mensagem === '') {
    respond(false, 'Preencha todos os campos obrigatórios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'E-mail inválido.');
}

$destino = 'thiago2023leal@gmail.com';
$assunto = '[Diagnóstico L-Vision] ' . $perfil . ' — ' . $nome;

$corpo = "Novo pedido de diagnóstico pelo site\n\n"
    . "Perfil: {$perfil}\n"
    . "Serviços de interesse: {$servicosTexto}\n"
    . "Nome: {$nome}\n"
    . "Telefone/WhatsApp: {$telefone}\n"
    . "E-mail: {$email}\n\n"
    . "Mensagem:\n{$mensagem}\n";

function resend_send_mail($to, $subject, $text, $replyTo) {
    $payload = json_encode([
        'from' => 'L-Vision Site <onboarding@resend.dev>',
        'to' => [$to],
        'subject' => $subject,
        'text' => $text,
        'reply_to' => $replyTo,
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . RESEND_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode >= 200 && $httpCode < 300;
}

$enviado = resend_send_mail($destino, $assunto, $corpo, $email);

if (!$enviado) {
    respond(false, 'Não foi possível enviar agora. Tente pelo WhatsApp ou e-mail direto.');
}

respond(true, 'Diagnóstico enviado com sucesso.');
