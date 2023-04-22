<?php

namespace Src\Core\Mail\mailSystem;

use PHPMailer\PHPMailer\PHPMailer as Mailer;
use Src\Core\Interfaces\MailerInterface;

class PhpMailer implements MailerInterface
{

  public static function send(array $config, mixed $mail): void
  {
    $mailer = new Mailer(true);
    $mailer->isSMTP();
    $mailer->Host       = $config['host'];
    $mailer->SMTPAuth   = true;
    $mailer->Port       = $config['port'];
    $mailer->Username   = $config['user'];
    $mailer->Password   = $config['pass'];
    $mailer->addAddress($mail['to']);
    $mailer->setFrom('support@email.com');
    $mailer->isHTML(true);
    $mailer->Subject = 'Here is the subject';
    $mailer->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mailer->send();
  }
}
