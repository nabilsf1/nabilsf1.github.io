<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Method Not Allowed';
  exit;
}

$to = 'nabilsf1@gmail.com';

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $subject === '' || $message === '') {
  http_response_code(400);
  echo 'Semua field wajib diisi.';
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo 'Format email tidak valid.';
  exit;
}

$safeName = preg_replace('/[\r\n]+/', ' ', $name);
$safeSubject = preg_replace('/[\r\n]+/', ' ', $subject);

$fullSubject = '[Portfolio Contact] ' . $safeSubject;
$body = "Nama: {$safeName}\n";
$body .= "Email: {$email}\n";
$body .= "\nPesan:\n{$message}\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: Portfolio Website <no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost') . '>';
$headers[] = 'Reply-To: ' . $email;

$sent = mail($to, $fullSubject, $body, implode("\r\n", $headers));

if ($sent) {
  echo 'OK';
  exit;
}

http_response_code(500);
echo 'Gagal mengirim email. Periksa konfigurasi mail server hosting.';
?>
