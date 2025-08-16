<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

require __DIR__ . '/config.php';

// 1) Validate input
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '')    $errors[] = 'Name is required';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
if ($subject === '') $errors[] = 'Subject is required';
if ($message === '') $errors[] = 'Message is required';

if ($errors) {
  http_response_code(422);
  echo json_encode(['ok' => false, 'error' => implode(', ', $errors)]);
  exit;
}

// 2) Save into DB (PDO + prepared statements)
try {
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
  $pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  $stmt = $pdo->prepare("
    INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent)
    VALUES (:name, :email, :subject, :message, :ip, :ua)
  ");
  $stmt->execute([
    ':name'    => $name,
    ':email'   => $email,
    ':subject' => $subject,
    ':message' => $message,
    ':ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
    ':ua'      => $_SERVER['HTTP_USER_AGENT'] ?? null,
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'DB error: '.$e->getMessage()]);
  exit;
}

// 3) Send Email via PHPMailer
try {
  require __DIR__ . '/vendor/autoload.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer(true);

  $mail->isSMTP();
  $mail->Host       = SMTP_HOST;
  $mail->SMTPAuth   = true;
  $mail->Username   = SMTP_USER;
  $mail->Password   = SMTP_PASS;
  $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = SMTP_PORT;

  $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
  $mail->addAddress(SMTP_TO);

  $mail->isHTML(true);
  $mail->Subject = 'New contact: ' . $subject;
  $mail->Body    = "
    <h2>New Contact Form Message</h2>
    <p><b>Name:</b> {$name}</p>
    <p><b>Email:</b> {$email}</p>
    <p><b>Subject:</b> {$subject}</p>
    <p><b>Message:</b><br>" . nl2br(htmlentities($message)) . "</p>
  ";
  $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";

  $mail->send();
} catch (Throwable $e) {
  // Email fail ho jaye to bhi DB me entry ho chuki hai; UI ko soft error dikhao
  echo json_encode(['ok' => true, 'email' => 'failed', 'reason' => $e->getMessage()]);
  exit;
}

// 4) Done
echo json_encode(['ok' => true, 'email' => 'sent']);
