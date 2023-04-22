<?php

namespace Src\Core\Mail\mailSystem;

use Src\Core\Interfaces\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class SymphonyMailer implements MailerInterface
{

  public static function send(array $config, mixed $mail)
  {
    $dsn = "smtp://{$config['user']}:{$config['pass']}@{$config['host']}:{$config['port']}?encryption=tls&auth_mode=login";
    $transport = Transport::fromDsn($dsn);
    $mailer = new Mailer($transport);
    $email = (new Email())
      ->from('hello@example.com')
      ->to($mail['to'])
      ->subject($mail['subject'])
      ->text($mail['text'] ?? '')
      ->html($mail['html'] ?? '');

    $mailer->send($email);
  }
}
