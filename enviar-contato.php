<?php
/**
 * Handler do formulário de diagnóstico (diagnostico.html).
 * Recebe POST, valida, envia e-mail via mail() nativo do PHP (sem dependências).
 */

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

$destino = 'thiagoleal@grupolvision.com.br';
$assunto = '[Diagnóstico L-Vision] ' . $perfil . ' — ' . $nome;

$corpo = "Novo pedido de diagnóstico pelo site\n\n"
    . "Perfil: {$perfil}\n"
    . "Serviços de interesse: {$servicosTexto}\n"
    . "Nome: {$nome}\n"
    . "Telefone/WhatsApp: {$telefone}\n"
    . "E-mail: {$email}\n\n"
    . "Mensagem:\n{$mensagem}\n";

$headers = "From: site@grupolvision.com.br\r\n"
    . "Reply-To: " . $email . "\r\n"
    . "Content-Type: text/plain; charset=UTF-8";

$enviado = @mail($destino, $assunto, $corpo, $headers);

if (!$enviado) {
    respond(false, 'Não foi possível enviar agora. Tente pelo WhatsApp ou e-mail direto.');
}

respond(true, 'Diagnóstico enviado com sucesso.');
